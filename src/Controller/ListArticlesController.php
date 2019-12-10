<?php


namespace App\Controller;


use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListArticlesController extends AbstractController
{
    /**
     * @return Response
     * @Route("/list")
     */
    public function listArticle ()
    {
        # Récupération des articles/produits
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        # Transmission à la vue
        return $this->render('shop/general/list.html.twig', [
            'articles' => $articles
        ]);
    }
}