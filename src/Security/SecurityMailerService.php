<?php

namespace App\Security;

use App\Entity\Token;
use App\Entity\User;
use App\Service\Traits\TranslatorAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SecurityMailerService
{
    use TranslatorAwareTrait;

    /** @var EntityManagerInterface */
    private $em;

    /** @var MailerInterface */
    private $mailer;

    /** @var TokenGenerator */
    private $generator;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, TokenGenerator $generator)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->generator = $generator;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function requestVerification(User $user): void
    {
        $token = new Token\UserVerificationToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $subject = $this->translator->trans('verify.subject', [], 'email');

        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate('email/verify.html.twig')
            ->context(
                [
                    'url' => $this->generator->generateUrl($token),
                    'subject' => $subject,
                ]
            );

        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function requestRecovery(User $user): void
    {
        $token = new Token\UserRecoveryToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $subject = $this->translator->trans('recovery.subject', [], 'email');
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate('email/recovery.html.twig')
            ->context(
                [
                    'url' => $this->generator->generateUrl($token),
                    'subject' => $subject,
                ]
            );

        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function invite(User $user): void
    {
        $token = new Token\UserInvitationToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $subject = $this->translator->trans('invite.subject', [], 'email');
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate('email/invite.html.twig')
            ->context(
                [
                    'url' => $this->generator->generateUrl($token),
                    'subject' => $subject,
                ]
            );

        $this->mailer->send($email);
    }
}
