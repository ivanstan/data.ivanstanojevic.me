<?php

namespace App\Security;

use App\Entity\User;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RecoveryService
{
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
    public function invite(User $user): int
    {
        $subject = 'Account Created';
        $body = $this->mailer->getTwig()->render('email/invite.html.twig', [
            'url' => $this->generateLoginUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    /**
     * @throws \Exception
     */
    public function request(User $user): int
    {
        $subject = 'Password Recovery';
        $body = $this->mailer->getTwig()->render('email/recovery.html.twig', [
            'url' => $this->generateLoginUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    public function recover(string $token): bool
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['token' => $token]);

        if (!$user) {
            return false;
        }

        $this->securityService->login($user);

        $user->setToken(null);
        $this->em->flush();

        return true;
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
