<?php

namespace App\Security;

use App\Entity\Token;
use App\Entity\User;
use App\Service\MailerService;
use App\Service\Traits\TranslatorAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityMailerService
{
    use TranslatorAwareTrait;

    /** @var EntityManagerInterface */
    private $em;

    /** @var MailerService */
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerService $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * @throws \Exception
     */
    public function requestVerification(User $user): int
    {
        $subject = $this->translator->trans('email.verify.subject');
        $body = $this->mailer->getTwig()->render('email/verify.html.twig', [
            'url' => $this->generateVerificationUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    /**
     * @throws \Exception
     */
    public function requestRecovery(User $user): int
    {
        $subject = $this->translator->trans('email.recovery.subject');
        $body = $this->mailer->getTwig()->render('email/recovery.html.twig', [
            'url' => $this->generateLoginUrl($user),
            'subject' => $subject
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    /**
     * @throws \Exception
     */
    public function invite(User $user): int
    {
        $subject = $this->translator->trans('email.invite.subject');
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
        $token = new Token($user, Token::TYPE_VERIFY);
        $this->em->persist($token);
        $this->em->flush();

        $this->em->flush();

        return $this->mailer->getGenerator()->generate('security_verification_token', ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * @throws \Exception
     */
    private function generateLoginUrl(User $user): string
    {
        $token = new Token($user);
        $this->em->persist($token);
        $this->em->flush();

        return $this->mailer->getGenerator()->generate('security_invitation_token', ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
