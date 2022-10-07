<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @Route("Route", name="RouteName")
     */
    public function search($search = null, $category = null, $city = null, $rayon = 0)
    {
        /*
         * Requête dans la bdd avec trois paramètres
         */
        $query = $this->createQueryBuilder('a') // 'a' est l'alias du repository où il est
                    ->select("a.name, a.price, a.description, a.id, ci.city_name as citName, ca.name as catName, u.first_name as uName")
                    ->join(Category::class, 'ca', Join::WITH, "a.category = ca.id")
                    ->join(City::class, 'ci', Join::WITH, "ci.id = a.city")
                    ->join(User::class, 'u', Join::WITH, "u.id = a.user")
                    ->where("a.deleted_at is null")
;

        if ($category) {
            $query->andWhere("ca.id = :category") // :nom_param évite les injections sql
            ->setParameter("category", $category);
        }
        
        if ($city) {
            // $query->andWhere("ci.id = :city")->setParameter("city", $city->getId());
            $query->addSelect("(ACOS(SIN(PI()*ci.latitude/180.0)*SIN(PI()*:lat2/180.0)+COS(PI()*ci.latitude/180.0)*COS(PI()*:lat2/180.0)*COS(PI()*:lon2/180.0-PI()*ci.longitude/180.0))*6371) AS dist")
            ->setParameter(":lat2", $city->getLatitude())
            ->setParameter(":lon2", $city->getLongitude())
            ->andHaving("dist <= :rayon")
            ->setParameter("rayon", $rayon); // andWhere permet d'ajouter une autre condition sans écraser la précédente
        }
              
        if ($search) {
            $query->andWhere("a.name LIKE :search")->setParameter("search", "%$search%");
        }

        // dd($query->getQuery());
        return $query   ->getQuery() // Génère la requête
                        ->getResult(); // Renvoie les résultats
    }


}
