<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityService
{
    private $requestStack;

    private $tokenStorage;

    private $eventDispatcher;

    private $session;

    public function __construct(
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session
    ) {
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }

    public function login(User $user): void
    {
        $token = new UsernamePasswordToken($user, null, 'common', $user->getRoles());
        $request = $this->requestStack->getMasterRequest();

        if (!$request->hasPreviousSession()) {
            $request->setSession($this->session);
            $request->getSession()->start();
            $request->cookies->set($request->getSession()->getName(), $request->getSession()->getId());
        }

        $this->tokenStorage->setToken($token);
        $this->session->set('_security_common', serialize($token));

        $event = new InteractiveLoginEvent($this->requestStack->getMasterRequest(), $token);
        $this->eventDispatcher->dispatch('security.interactive_login', $event);
    }
}
