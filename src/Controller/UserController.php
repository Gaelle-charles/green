<?php


namespace App\Controller;

use App\Entity\Trader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class KiltyConnexionController
 * @package App\Controller
 */
class UserController extends AbstractController
{


    /**
     * @Route("/register", name="shop_register", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {

        $trader = new Trader();
        $trader->setRoles(['ROLE_MEMBRE'])->setRegistrationDate(new \DateTime());

        $form = $this->createFormBuilder($trader)
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisissez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisissez votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisissez votre email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisissez votre mot de passe'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Je m'inscris !",
                'attr' => [
                    'class' => 'btn btn-block btn-dark'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($trader); // FIXME a supprimer

        }

        return $this->render('shop/user/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

}