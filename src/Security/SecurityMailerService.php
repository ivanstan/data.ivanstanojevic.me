<?php

namespace App\Security;

use App\Entity\Token;
use App\Entity\User;
use App\Service\MailerService;
use App\Service\Traits\TranslatorAwareTrait;
use Doctrine\ORM\EntityManagerInterface;

class SecurityMailerService
{
    use TranslatorAwareTrait;

    /** @var EntityManagerInterface */
    private $em;

    /** @var MailerService */
    private $mailer;

    /** @var TokenGenerator */
    private $generator;

    public function __construct(EntityManagerInterface $em, MailerService $mailer, TokenGenerator $generator)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->generator = $generator;
    }

    /**
     * @throws \Exception
     */
    public function requestVerification(User $user): int
    {
        $token = new Token\UserVerificationToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $subject = $this->translator->trans('verify.subject', [], 'email');
        $body = $this->mailer->getTwig()->render('email/verify.html.twig', [
            'url' => $this->generator->generateUrl($token),
            'subject' => $subject,
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    /**
     * @throws \Exception
     */
    public function requestRecovery(User $user): int
    {
        $token = new Token\UserRecoveryToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $subject = $this->translator->trans('recovery.subject', [], 'email');
        $body = $this->mailer->getTwig()->render('email/recovery.html.twig', [
            'url' => $this->generator->generateUrl($token),
            'subject' => $subject,
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    /**
     * @throws \Exception
     */
    public function invite(User $user): int
    {
        $token = new Token\UserInvitationToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $subject = $this->translator->trans('invite.subject', [], 'email');
        $body = $this->mailer->getTwig()->render('email/invite.html.twig', [
            'url' => $this->generator->generateUrl($token),
            'subject' => $subject,
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }
}
