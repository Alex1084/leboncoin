<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'app_category_')]
class CategoryController extends AbstractController
{

    #[Route("/list", name : "list")]
    public function list(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine) : Response
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();
        // dd($categories);
        return $this->render('category/list.html.twig', [
            "categories" => $categories
        ]);
    }

    #[Route("/add", name : "add")]
    public function addCategory(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine)
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryFormType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("app_category_list");
        }

        return $this->render("/category/form.html.twig", [
            "categoryForm" => $categoryForm->createView()
        ]);
    }

    #[Route("/update/{id}", name : "update")]
    public function updateCategory($id, Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine)
    {
        $category = $doctrine->getRepository(Category::class)->find($id);
        $categoryForm = $this->createForm(CategoryFormType::class, $category);

        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("app_category_list");
        }
        return $this->render("/category/form.html.twig", [
            "categoryForm" => $categoryForm->createView()
        ]);
    }
}
