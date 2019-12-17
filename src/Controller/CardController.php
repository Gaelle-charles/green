<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/panier", name="shop_panier")
     * @param SessionInterface $session
     * @param ArticleRepository $articleRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    # On recupère la Session et on créé un panier pour y ajouter un article


    public function index(SessionInterface $session, ArticleRepository $articleRepository)
    {

        $panier = $session->get('panier', []);

        #déclaration d'un tableau vide
        $panierWithData = [];


        foreach ($panier as $id => $quantity) {
            # on rajoute dans notre panier vide
            # un tableau ass.
            $panierWithData[] = [
                #celle-ci contient une case article et quantité
                #recuperation des données.
                'article' => $articleRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;
        #Calcul du total d'un produit
        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrice() * $item['quantity'];

            #rajouter le total de l'article au total
            $total += $totalItem;
        }
        return $this->render('shop/general/Panier.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, SessionInterface $session)
    {
        # dans la session on verifie si il y a une données Panier avec la fonction "get"
        # sinon, on retourne un tableau vide .
        $panier = $session->get('panier', []);

        # si mon panier n'est pas vide ( que j'ai déja l'article) on rajoute l'article
        if (!empty($panier[$id])) {
            $panier[$id]++;

        } else {
            $panier[$id] = 1;
        }

        #ce qu'il y avait auparavant dans mon panier, je remplace par mon nouveau panier qui à été altéré
        $session->set('panier', $panier);
        return $this->redirectToRoute("shop_panier");
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     * @param $id
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        #reinitialisation du panier
        $session->set('panier', $panier);
        return $this->redirectToRoute("shop_panier");
    }
}
