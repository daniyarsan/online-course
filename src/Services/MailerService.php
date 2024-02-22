<?php

namespace App\Services;

use App\Entity\Message;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendContactMessage(Message $message): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from('yourquiz@yourquiz.io')
            ->to('manager@yourquiz.io')
            ->subject('Новое сообщение из формы контактов')
            ->htmlTemplate('mailer/main._html.twig')->context([
                'data' => $message
            ]);

        $this->mailer->send($email);

        return $email;
    }

    public function sendAnonContactMessage(Message $message)
    {
        $email = (new TemplatedEmail())
            ->from('yourquiz@yourquiz.io')
            ->to('manager@yourquiz.io')
            ->subject('Новое Анонимное сообщение из формы контактов')
            ->htmlTemplate('mailer/anon._html.twig')->context([
                'data' => $message
            ]);

        $this->mailer->send($email);

        return $email;
    }
}