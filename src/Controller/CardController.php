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
     * @param ArticleRepository $productRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository)
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' =>$articleRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $total = 0;
        foreach ($panierWithData as $item){
            $totalItem=$item['article']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('shop/Panier.html.twig', [
            'items' => $panierWithData,
            'total'=> $total
        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, SessionInterface $session)
    {
        #$session = $request->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
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
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("shop_panier");
    }
}