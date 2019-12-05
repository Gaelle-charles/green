<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class KiltyShopController
 * @package App\Controller
 * @Route("/boutique")
 */
class KiltyShopController extends AbstractController
{
    /**
     * Shop Homepage
     * @Route("/", name="shop_home", methods={"GET"})
     */
    public function home()
    {
        return $this->render('shop/home.html.twig');
    }
}