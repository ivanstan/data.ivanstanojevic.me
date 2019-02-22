<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Watchdog;
use App\Service\WatchdogAwareTrait;
use App\Service\WatchdogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SecuritySubscriber implements EventSubscriberInterface
{
    use WatchdogAwareTrait;

    private $em;

    private $tokenStorage;

    private $requestStack;

    private $authenticationUtils;

    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        AuthenticationUtils $authenticationUtils
    ) {

        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->authenticationUtils = $authenticationUtils;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
        ];
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $username = $this->authenticationUtils->getLastUsername();
        $ip = $this->requestStack->getCurrentRequest()->getClientIp();

        $this->watchdog->log(
            AuthenticationEvents::AUTHENTICATION_FAILURE,
            sprintf('Login attempt failure with email %s from remote address %s', $username, $ip),
            $ip,
            Watchdog::WARNING
        );
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $user->setLastLogin(new \DateTime('now', new \DateTimeZone('UTC')));
        $user->setIp($this->requestStack->getCurrentRequest()->getClientIp());

        $this->em->flush();

        $this->watchdog->log(
            SecurityEvents::INTERACTIVE_LOGIN,
            sprintf('Open session for user %s', $user->getEmail()),
            Watchdog::WARNING
        );
    }
}
