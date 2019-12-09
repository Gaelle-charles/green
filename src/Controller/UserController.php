<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * Class KiltyConnexionController
 * @package App\Controller
 */
class UserController extends AbstractController
{


    /**
     * @Route("/register", name="shop_register", methods={"GET|POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $user->setRoles(['ROLE_USER'])->setRegistrationDate(new \DateTime());

        $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Saisissez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Saisissez votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Saisissez votre email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Saisissez votre mot de passe'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Je m'inscris !",
                'attr' => [
                    'class' => 'btn btn-block btn-info'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        # 3. Vérification de la soumission
        if ($form->isSubmitted() && $form->isValid()) {


            $user->setPassword(
                $encoder->encodePassword($user, $user->getPassword())
            );
                  # 5. Sauvegarde en BDD

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # 6.Notification flash
            $this->addFlash('notice',
                'Félicitation vous êtes inscris !');

            # 7. Redirection sur la page de Connexion
            return $this->redirectToRoute('shop_home');
        }

        #Transmission du Formulaire a la vue
        return $this->render('shop/user/register.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/login", name="shop_login", methods={"GET|POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login( AuthenticationUtils $authenticationUtils ):Response
    { //
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('shop/user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion.html", name="app_logout")
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }







    }
