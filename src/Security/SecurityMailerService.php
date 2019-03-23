<?php

namespace App\Security;

use App\Entity\Token;
use App\Entity\User;
use App\Service\System\MailerService;
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

    public function requestVerification(User $user): int
    {
        $subject = $this->translator->trans('verify.subject', [], 'email');
        $body = $this->mailer->getTwig()->render('email/verify.html.twig', [
            'url' => $this->generateUrl($user, SecurityService::VERIFICATION),
            'subject' => $subject,
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    public function requestRecovery(User $user): int
    {
        $subject = $this->translator->trans('recovery.subject', [], 'email');
        $body = $this->mailer->getTwig()->render('email/recovery.html.twig', [
            'url' => $this->generateUrl($user, SecurityService::RECOVERY),
            'subject' => $subject,
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    public function invite(User $user): int
    {
        $subject = $this->translator->trans('invite.subject', [], 'email');
        $body = $this->mailer->getTwig()->render('email/invite.html.twig', [
            'url' => $this->generateUrl($user, SecurityService::INVITATION),
            'subject' => $subject,
        ]);

        return $this->mailer->send($subject, $body, $user->getEmail());
    }

    private function generateUrl(User $user, string $type): string
    {
        $token = new Token($user, SecurityService::VERIFICATION === $type ? Token::TYPE_VERIFICATION : Token::TYPE_RECOVERY);
        $this->em->persist($token);
        $this->em->flush();

        $this->em->flush();

        return $this->mailer->getGenerator()->generate($type, ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
