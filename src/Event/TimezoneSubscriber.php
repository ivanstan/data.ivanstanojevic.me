<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TimezoneSubscriber implements EventSubscriberInterface
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest() || !$this->token->getToken()) {
            return;
        }

        /** @var User $user */
        $user = $this->token->getToken()->getUser();

        if ($user instanceof User && $user->getPreference() && $user->getPreference()->getTimezone()) {
            $timezone = $user->getPreference()->getTimezone();

            date_default_timezone_set($timezone);
        }
    }
}
