<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    public function Mail($name, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                )

            )
        ;

        $mailer->send($message);

        return $this->render('general.html.twig');
    }

}