<?php

namespace App\Security;

use App\Entity\User;
use App\Service\MailerService;
use App\Service\Traits\TranslatorAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityMailerService
{
    use TranslatorAwareTrait;

    private const TOKEN_LENGTH = 24;

    private $em;

    private $mailer;

    private $securityService;

    public function __construct(
        MailerService $mailer,
        EntityManagerInterface $em,
        SecurityService $securityService
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->securityService = $securityService;
    }

    /**
     * @throws \Exception
     */
    public function requestVerification(User $user): int
    {
        $subject = $this->translator->trans('Account Verification');
        $body = $this->mailer->getTwig()->render('email/verify.html.twig', [
            'url' => $this->generateVerificationUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    public function verify(string $token): ?User
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['token' => $token]);

        if (!$user) {
            return null;
        }

        $this->securityService->login($user);

        $user->setToken(null);
        $user->setVerified(true);
        $this->em->flush();

        return $user;
    }

    /**
     * @throws \Exception
     */
    public function requestRecovery(User $user): int
    {
        $subject = $this->translator->trans('Password Recovery');
        $body = $this->mailer->getTwig()->render('email/recovery.html.twig', [
            'url' => $this->generateLoginUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    public function recover(string $token): ?User
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['token' => $token]);

        if (!$user) {
            return null;
        }

        $this->securityService->login($user);

        $user->setToken(null);
        $user->setVerified(true);
        $this->em->flush();

        return $user;
    }

    /**
     * @throws \Exception
     */
    public function invite(User $user): int
    {
        $subject = $this->translator->trans('account.created');
        $body = $this->mailer->getTwig()->render('email/invite.html.twig', [
            'url' => $this->generateLoginUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    /**
     * @throws \Exception
     */
    private function generateVerificationUrl(User $user): string
    {
        $token = $this->generateToken();
        $user->setToken($token);
        $this->em->flush();

        return $this->mailer->getGenerator()->generate('security_verification_token', ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @throws \Exception
     */
    private function generateLoginUrl(User $user): string
    {
        $token = $this->generateToken();
        $user->setToken($token);
        $this->em->flush();

        return $this->mailer->getGenerator()->generate('security_invitation_token', ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @throws \Exception
     */
    private function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH));
    }
}
