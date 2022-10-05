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

class CityController extends AbstractController
{
    #[Route('/city', name: 'app_city')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $city = $doctrine->getRepository(City::class)->findAll();

        return $this->render('city/index.html.twig', [
            'controller_name' => 'CityController',
        ]);
    }

    #[Route('/city/form', name: 'app_city_add')]
    public function addCity(Request $request, EntityManagerInterface $em): Response
    {
        $city = new City();
        $cityForm = $this->createForm(CityFormType::class, $city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $em->persist($city);
            $em->flush();
            return $this->redirectToRoute('app_city');
        }

        // dd($city);

        return $this->render('city/form.html.twig', [
            'controller_name' => 'CityController',
            'cityForm' => $cityForm->createView(),
        ]);
    }

    #[Route('/city/form/{id}', name: 'app_city_update')]
    public function updateCity(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, $id): Response
    {
        $city = $doctrine->getRepository(City::class)->find($id); // Recherche dans la bdd la ville dont l'id correspond

        $cityForm = $this->createForm(CityFormType::class, $city); // Création du formulaire + remplissage des infos
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $em->persist($city);
            $em->flush();
            return $this->redirectToRoute('app_city');
        }

        return $this->render('city/form.html.twig', [
            'controller_name' => 'CityController',
            'cityForm' => $cityForm->createView(),
        ]);
    }
}
