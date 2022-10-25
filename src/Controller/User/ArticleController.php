<?php

namespace App\Controller\User;

use App\Entity\Article;
use App\Entity\ArticleImage;
use App\Form\ArticleFormType;
use App\Services\UploadService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/add', name: 'app_article_add')]
    public function addArticle(EntityManagerInterface $em, Request $request, UploadService $uploadService): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash("error", "vous n'êtes pas connécter");
            return $this->redirectToRoute("app_login");
        }
        $article = new Article();
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $article->setUser($user)->setCreatedAt(new DateTimeImmutable());
            foreach ($articleForm->get("images")->getData() as $imageFile) {
                if ($imageFile !== null) {
                    // dump($image);
                    $filename = $uploadService->upload($imageFile["image"], "article");
                    $image = new ArticleImage();
                    $image->setImage($filename);
                    $article->addImages($image);
                }
            }
            $em->persist($article);
            $em->flush();
            $this->addFlash("succes", "votre article est desomais disponible a la vente");
            return $this->redirectToRoute("app_home");
        }
        return $this->render('user/article/form.html.twig', [
            "articleForm" => $articleForm->createView()
        ]);
    }

    #[Route('/article/update/{id}', name: 'app_article_update')]
    public function updateArticle(int $id, EntityManagerInterface $em, Request $request, UploadService $uploadService): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
        if (!$article || $article->getDeletedAt() !== null) {
            $this->addFlash("error", "l'article demander n'existe pas");
            return $this->redirectToRoute("app_home");
        }
        $user = $this->getUser();
        if (!$user || $user !== $article->getUser() ) {
            // $this->addFlash("error", "vous ne pouvez pas");
            return $this->redirectToRoute("app_login");
        }
        
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $article->setUser($user)->setUpdatedAt(new DateTimeImmutable());
            foreach ($articleForm->get("images")->getData() as $imageFile) {
                if ($imageFile !== null) {
                    // dump($image);
                    $filename = $uploadService->upload($imageFile["image"], "article");
                    $image = new ArticleImage();
                    $image->setImage($filename);
                    $article->addImages($image);
                }
            }
            $em->persist($article);
            $em->flush();
            $this->addFlash("succes", "votre article est desomais disponible a la vente");
            return $this->redirectToRoute("app_home");
        }
        return $this->render('user/article/form.html.twig', [
            "articleForm" => $articleForm->createView(),
            "article" => $article
        ]);
    }

    #[Route('/article/my-article', name: 'app_article_user')]
    public function getArticleByUser(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash("error", "vous n'êtes pas connécter");
            return $this->redirectToRoute("app_login");
        }
        $articles = $em->getRepository(Article::class)->findBy(["user" => $user, "deleted_at" => null]);
        return $this->render('user/article/list-by-user.html.twig', [
            "articles" => $articles
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete')]
    public function deleteArticle(int $id, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
        if (!$article || $article->getDeletedAt() !== null) {
            $this->addFlash("error", "l'article demander n'existe pas");
            return $this->redirectToRoute("app_home");
        }
        $user = $this->getUser();
        if (!$user || $user !== $article->getUser() ) {
            return $this->redirectToRoute("app_login");
        }
        $article->setDeletedAt(new DateTimeImmutable());
        $em->persist($article);
        $em->flush();
        $this->addFlash("succes", "votre article a bien été suprimmer");
        return $this->redirectToRoute("app_home");
    }
}
