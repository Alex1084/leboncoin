<?php

namespace App\Controller;

use App\Services\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    /* #[Route('/favorite', name: 'app_favorite')]
    public function index(): Response
    {
        return $this->render('favorite/index.html.twig', [
            'controller_name' => 'FavoriteController',
        ]);
    } */

    #[Route('/favorite/add/{id}', name: 'favorite_add')]
    public function add($id, SessionInterface $sessionInterface ): Response
    {
        $favorite = $sessionInterface->get('favorite');
        $data = "add";
        if (!$favorite) {
            $favorite = [];
        }
        $key = array_search($id, $favorite);
        if ( $key === false) {
            array_push($favorite, $id);
        }
        else {
            $data = "del";
            array_splice($favorite, $key, 1);
        }

        $sessionInterface->set('favorite', $favorite);
        /* else {
            array_
        } */
        // return $this->json("success",200);
        return $this->json(["success", $data],200);
    }

    #[Route("/favorite/delete", name:"favorite_del")]
    public function deleteSession(SessionInterface $sessionInterface)
    {
        $sessionInterface->set("favorite", null);
        return $this->redirectToRoute("app_articles_navigation");
    }
}
