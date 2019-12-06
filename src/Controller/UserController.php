<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    public function formArticle()
    {
        # 1. Création du formulaire
        $form =$this->createFormBuilder()
            ->add('', )
    }
}