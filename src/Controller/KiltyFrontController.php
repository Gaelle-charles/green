<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class KiltyFrontController
 * @package App\Controller
 * @Route("/compte")
 */
class KiltyFrontController extends AbstractController

{
    /**
     * @Route("/", name="front_account", methods={"GET"})
     *
     */
    public function account()
    {
        return $this -> render('indexLayout2.html.twig');
    }
}