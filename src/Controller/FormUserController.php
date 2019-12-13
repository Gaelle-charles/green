<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormUserController extends AbstractController
{
    use HelperTrait;

    /**
     * Formulaire pour ajouter des articles
     * @Route("/ajouter-un-article", name="article_add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return Response
     */

    public function addArticle(Request $request)
    {

        # Création de nouvel article
        $article = new Article();
        $article->setUser($this->getUser());

        # Création du formulaire
        $form = $this->createFormBuilder($article)

            # Titre de l'article
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' =>[
                    'placeholder' => 'Titre de l\'Article ...'
                ]
            ])

            # Catégorie
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'choice_label' => 'name'
            ])

            # Contenu
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' =>'Description l\'article'
            ])

            # Price
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Prix'
            ])

            # Quantity
            ->add('quantity', NumberType::class, [
                'label' =>'Quantité vendue'
            ])

            # Image
            ->add('image', FileType::class,[
                'label' => 'Photos (fortement conseillée)',
                'attr' =>[
                    'class' => 'dropify'
                ]
            ])

            # Bouton Envoyer
            ->add('submit', SubmitType::class, [
                'label' => 'Publier mon article',
                'attr' => [
                    'class' => 'btn btn-block btn-info'
                ]
            ])

            # Récupére les données dans le Formulaire
            ->getForm();

        # Gestion des données reçues par symfony
        $form->handleRequest($request);

        # Soumission du form et validation
        if ( $form->isSubmitted() && $form->isValid()) {

            # -- Upload de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();
            if ($imageFile){

                # --------------------- ❌ NE PAS OUBLIER LA ROUTE ❌ ------------

                $newFilename = $this->slugify($article->getTitle()). '-' . uniqid(). '.'. $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('products_images'),
                        $newFilename
                    );
                } catch (FileException $e){

                }
                $article->setImage($newFilename);

            }
            // Fin de upload de l'image

            # Génération de l'alias de l'article
            $article->setAlias( $this->slugify( $article->getTitle()));

            # Sauvegarde en bdd
            $em = $this->getDoctrine()->getManager();
            $em -> persist($article);
            $em -> flush();

            # Notification
            $this ->addFlash('notice',
                'Votre article est désormais en ligne !');

            # -------------- ❌ NE PAS OUBLIER LA ROUTE ❌------------
            # Redirection
            return $this->redirectToRoute('shop_home', [
                'category' => $article-> getCategory() -> getAlias(),
                'alias' => $article -> getAlias(),
                'id' => $article-> getId()
            ]);
        }

        # Transmission à la Vue
        return $this->render('shop/user/formUser.html.twig',[
            'form' => $form ->createView()
        ]);
    }
}