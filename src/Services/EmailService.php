<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(#[Autowire('%email.from%')] private readonly string $from, private readonly MailerInterface $mailer)
    {

    }

    public function send(string $content, string $to, string $subject): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->text($content)
        ;
        $this->mailer->send($email);
    }
}