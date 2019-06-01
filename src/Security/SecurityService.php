<?php

namespace App\Security;

use App\Entity\Token\UserRecoveryToken;
use App\Entity\Token\UserToken;
use App\Entity\Token\UserVerificationToken;
use App\Entity\User;
use App\Service\DateTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityService
{
    public const TOKEN_VALIDITY_INTERVAL = 'P1D';

    /** @var EntityManagerInterface */
    private $em;

    /** @var RequestStack */
    private $requestStack;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        EntityManagerInterface $em,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session
    ) {
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->em = $em;
    }

    public function login(User $user): void
    {
        $token = new UsernamePasswordToken($user, null, 'common', $user->getRoles());
        $request = $this->requestStack->getMasterRequest();

        if ($request && !$request->hasPreviousSession()) {
            $request->setSession($this->session);
            $session = $request->getSession();

            if ($session) {
                $session->start();
                $request->cookies->set($session->getName(), $session->getId());
            }
        }

        $this->tokenStorage->setToken($token);
        $this->session->set('_security_common', serialize($token));

        $event = new InteractiveLoginEvent($this->requestStack->getMasterRequest(), $token);
        $this->eventDispatcher->dispatch('security.interactive_login', $event);
    }

    public function verify(string $token): ?User
    {
        $token = $this->em->getRepository(UserToken::class)->getToken($token, UserVerificationToken::class);

        return $this->loginAndVerify($token);
    }

    public function recover(string $token): ?User
    {
        $token = $this->em->getRepository(UserToken::class)->getToken($token, UserRecoveryToken::class);

        return $this->loginAndVerify($token);
    }

    private function loginAndVerify(?UserToken $token): ?User {
        if (!$token || !$token->isValid(DateTimeService::getCurrentUTC())) {
            return null;
        }

        /** @var User $user */
        $user = $token->getUser();

        $this->login($user);

        $user->setVerified(true);
        $this->em->remove($token);
        $this->em->flush();

        return $user;
    }
}
