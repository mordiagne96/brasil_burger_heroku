<?php

namespace App\Services;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailerService{

    private Environment $twig;
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendEmail(MailerInterface $mailer, Environment $twig)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Activation de compte')
            ->text('Sending emails is fun again!')
            // ->html('<p>CLiquez sur ce lien pour valider!</p>');
            ->html($this->twig->render("mailer/index.html.twig"));

        $mailer->send($email);

        // return "json";
        // ...
    }
}