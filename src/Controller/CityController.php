<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/city', name: 'app_city_')]
class CityController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): Response
    {
        $cities = $doctrine->getRepository(City::class)->findAll();

        return $this->render('city/index.html.twig', [
            'cities' => $cities
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addCity(Request $request, EntityManagerInterface $em): Response
    {
        $city = new City();
        $cityForm = $this->createForm(CityFormType::class, $city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $em->persist($city);
            $em->flush();
            return $this->redirectToRoute('app_city_list');
        }

        return $this->render('/city/form.html.twig', [
            'cityForm' => $cityForm->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateCity(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, $id)
    {
        $city = $doctrine->getRepository(City::class)->find($id); // Recherche dans la bdd la ville dont l'id correspond
        $cityForm = $this->createForm(CityFormType::class, $city); // Création du formulaire + remplissage des infos
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $em->persist($city);
            $em->flush();
            return $this->redirectToRoute('app_city_list');
        }

        return $this->render('/city/form.html.twig', [
            'cityForm' => $cityForm->createView(),
        ]);
    }
}
