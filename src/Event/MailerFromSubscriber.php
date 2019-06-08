<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;

class MailerFromSubscriber implements EventSubscriberInterface
{
    /** @var string */
    private $appName;

    /** @var string */
    private $mailFrom;

    public function __construct($mailFrom, $appName)
    {
        $this->appName = $appName;
        $this->mailFrom = $mailFrom;
    }

    public static function getSubscribedEvents(): array
    {
        return [MessageEvent::class => 'onMessageSend'];
    }

    public function onMessageSend(MessageEvent $event): void
    {
        $message = $event->getMessage();

        if (!$message instanceof Email) {
            return;
        }

        $message->from($this->mailFrom);
    }
}
