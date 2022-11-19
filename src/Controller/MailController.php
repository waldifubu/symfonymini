<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function sendEmail(NotifierInterface $notifier): Response
    {
        // Create a Notification that has to be sent
        // using the "email" channel
        $notification = (new Notification('Your New Invoice from '.date('d.m.Y'), []))
            ->content('You got a new invoice for 15 EUR.')
            ->importance(Notification::IMPORTANCE_LOW);

        $user = [];
        $user['email'] = 'john_dow@example.org';
        $user['phonenumber'] = '+123456789';

        // The receiver of the Notification
        $recipient = new Recipient(
            $user['email'],
            $user['phonenumber'],
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);

//        $this->addFlash('success', 'Article Created! Knowledge is power!');
//        $this->addFlash('notice', 'Your changes were saved! '.$user['email']);
//        $this->addFlash('notification', 'NOTE: Your changes were saved! '.$user['email']);

        return new Response('nix');
    }

    #[Route('/mailadmin', name: 'app_mail2')]
    public function sendEmail2(Request $request, Session $session): Response
    {
        //$bag = $request->getSession()->getFlashBag()->
        $bag = $session->getFlashBag()->peekAll();

//        dd($bag);

//        $not= $flashBag->get('notification');
//        var_dump($not);
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }

    #[Route('/email', name: 'app_mail3')]
    public function sendEmail3(MailerInterface $mailer): Response
    {
        $address = new Address('john_doe@example.com', 'Waldis Email');

        $email = (new TemplatedEmail())
            // ->from()
            ->to($address)
            ->subject('Voll wichtiges Thema und so weiter')
            ->htmlTemplate('emails/signup.html.twig')
//            ->textTemplate('emails/signup.txt.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ]);

        $mailer->send($email);

        return new Response('Sent email');
    }

    #[Route('/mailgun', name: 'app_mail4')]
    public function sendEmail4(MailerInterface $mailer): Response
    {
        $address = new Address('john_doe@example.com', 'Waldis Email');

        $email = (new TemplatedEmail())
            // ->from()
            ->to($address)
            ->subject('Voll wichtiges Thema und so weiter')
            ->htmlTemplate('emails/signup.html.twig')
//            ->textTemplate('emails/signup.txt.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ]);

        $email->getHeaders()->addTextHeader('X-Transport', 'mailgun');
        $mailer->send($email);

        return new Response('Sent email');
    }



}
