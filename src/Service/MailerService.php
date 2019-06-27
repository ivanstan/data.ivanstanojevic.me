<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerService
{
    /** @var string */
    private $mailFrom;

    /** @var \Swift_Mailer */
    private $mailer;

    public function __construct($mailFrom, \Swift_Mailer $mailer)
    {
        $this->mailFrom = $mailFrom;
        $this->mailer = $mailer;
    }

    public function send(Email $email): void
    {
        /** @var Address $address */
        foreach ($email->getTo() as $address) {
            $message = (new \Swift_Message($email->getSubject()))
                ->setFrom($this->mailFrom)
                ->setTo($address->getEncodedAddress())
                ->setBody($email->getHtmlBody())
                ->addPart($email->getTextBody(), 'text/plain');

            $this->mailer->send($message);
        }
    }
}
