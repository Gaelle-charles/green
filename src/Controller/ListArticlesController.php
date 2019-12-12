<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListArticlesController extends AbstractController
{
    /**
     * @return Response
     * @Route("/index", name="default_index")
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

    /**
     * @Route("/category", name="default_category", methods={"GET"})
     * @return Response
     */

    public function category($alias)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['alias' => $alias]);

        $articles = $category->getArticles();

        return $this->render('shop/general/listCategory.html.twig', [
            'articles' => $articles,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category}/{alias}_{id}.html", name="default_article", methods={"GET"})
     * @return Response
     */
    public function article(Article $article)
    {
        return $this->render('shop/general/article.html.twig', [
            'article' => $article
        ]);
    }

    public function menu()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('shop/general/list.html.twig',[
            'categories' => $categories
        ]);
    }
}