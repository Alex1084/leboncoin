<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category', name: 'app_category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        // $category = new Category();
        // $category->setName("Mode");

        // $em->persist($category); //Génère la requête pour savoir dans quelle table se placer et quoi afficher
        // $em->flush(); //affiche la requête

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/list', name : 'list')]
    public function list(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine) : Response
    {
        dd($request);

        $categories = $doctrine->getRepository(Category::class)->findAll();
        // dd($categories); //dump and die
        dump($categories); //affiche également le contenu de la page

        return $this->render('category/list.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
