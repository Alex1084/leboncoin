<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticlesNavigationController extends AbstractController
{
    #[Route('/articles/navigation', name: 'app_articles_navigation')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        
        $articleForm = $this->createForm(ArticleSearchType::class); // Création du formulaire + remplissage des infos
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()){
            $articles = $doctrine->getRepository(Article::class)->search($articleForm->get("search")->getData(), $articleForm->get("category")->getData(), $articleForm->get("city")->getData(), $articleForm->get("rayon")->getData());
            // dd($articles);
        }
        else {
            $articles = $doctrine->getRepository(Article::class)->search();
        }

        return $this->render('articles_navigation/index.html.twig', [
            'articles' => $articles,
            'ArticleSearchForm' => $articleForm->createView()
        ]);
    }


}
