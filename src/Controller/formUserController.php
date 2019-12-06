<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class formUserController extends AbstractController
{
    /**
     * Formulaire pour ajouter des articles
     * @Route("/ajouter-un-article", name="article_add")
     * @param Request $request
     * @return Response
     */

    public function addArticle(Request $request)
    {

        # Création de nouvel article
        $article = new Article();

        # Création du formulaire
        $form =$this->createFormBuilder($article)

            # Titre de l'article
            ->add('title', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Titre de l\'Article ...'
                ]
            ])

            # Catégorie
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'label' =>false,
                'choise_label' => 'name'
            ])

            # Contenu
            ->add('content', TextareaType::class,[
                'required' => false,
                'label' =>false,
            ])

            # Image
            ->add('image', FileType::class, [
                'label' => false,
                'attr' =>[
                    'class' => 'dropify'
                ]
            ])

            # Bouton Envoyer
            ->add('submit', SubmitType::class,[
                'label' => 'Mettre en ligne mon article'
            ])

            # Récupére les données dans le Formulaire
            ->getForm();

        $form->handleRequest($request);

        # Soumission du form et validation
        if ( $form->isSubmitted() && $form->isValid()) {

            # -- Upload de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();
            if ($imageFile){

                # --------------------- ❌ NE PAS OUBLIER LE LA ROUTE ❌------------

                $newFilename = $this->slugify($article->getTitle()). '-' . uniqid(). '.'. $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter(''),
                        $newFilename
                    );
                } catch (FileException $exception){

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

            # -------------- ❌ NE PAS OUBLIER LE LA ROUTE ❌------------

            # Redirection
            return $this->redirectToRoute('', [
                'category' => $article-> getCategory() -> getAlias(),
                'alias' => $article -> getAlias(),
                'id' => $article-> getId()
            ]);
        }

        # -------------- ❌ NE PAS OUBLIER LE LA ROUTE ❌------------

        # Transmission à la Vue
        return $this->render('',[
            'form' => $form ->createView()
        ]);
    }
}