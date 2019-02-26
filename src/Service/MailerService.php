<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

class MailerService
{
    /** @var \Swift_Mailer  */
    private $mailer;

    /** @var EngineInterface  */
    private $twig;

    /** @var UrlGeneratorInterface  */
    private $generator;

    /** @var string */
    private $mailFrom;

    public function __construct(
        \Swift_Mailer $mailer,
        EngineInterface $twig,
        UrlGeneratorInterface $generator,
        $mailFrom
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->generator = $generator;
        $this->mailFrom = $mailFrom;
    }

    public function send(string $subject, string $body, string $recipient): int
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($this->mailFrom)
            ->setTo($recipient)
            ->setBody($body, 'text/html');

        return $this->mailer->send($message);
    }

    public function getMailer(): \Swift_Mailer
    {
        return $this->mailer;
    }

    public function getTwig(): EngineInterface
    {
        return $this->twig;
    }

    public function getGenerator(): UrlGeneratorInterface
    {
        return $this->generator;
    }
}
