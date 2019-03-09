<?php

namespace App\Security;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityService
{
    public const VERIFICATION = 'security_verification_token';
    public const INVITATION = 'security_invitation_token';
    public const RECOVERY = 'security_recovery_token';

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

    public function verify(string $token): ?User
    {
        $token = $this->em->getRepository(Token::class)->getVerificationToken($token);

        return $this->loginAndVerify($token);
    }

    public function recover(string $token): ?User
    {
        $token = $this->em->getRepository(Token::class)->getRecoveryToken($token);

        return $this->loginAndVerify($token);
    }

    private function loginAndVerify(Token $token): ?User {
        if (!$token || !$token->getUser() || !$token->isValid(self::TOKEN_VALIDITY_INTERVAL)) {
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
