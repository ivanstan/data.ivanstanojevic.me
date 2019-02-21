<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SecuritySubscriber implements EventSubscriberInterface
{
    private $em;

    private $tokenStorage;
    /** @var RequestStack */
    private $requestStack;

    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ) {

        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $user->setLastLogin(new \DateTime('now', new \DateTimeZone('UTC')));
        $user->setIp($this->requestStack->getCurrentRequest()->getClientIp());

        $this->em->flush();
    }
}
