<?php

namespace App\EventSubscriber;

use App\Entity\Lock;
use App\Entity\User;
use App\Service\System\DateTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Templating\EngineInterface;

class SecuritySubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const LOGIN_ATTEMPT_FAILURE = 'login_attempt_failure';
    public const BAN_AFTER_ATTEMPTS = 5;

    /** @var EntityManagerInterface */
    private $em;

    /** @var RequestStack */
    private $request;

    /** @var EngineInterface */
    private $twig;

    public function __construct(EntityManagerInterface $em, RequestStack $request, EngineInterface $twig)
    {
        $this->em = $em;
        $this->request = $request;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $lock = $this->em->getRepository(Lock::class)->getLock(
            self::LOGIN_ATTEMPT_FAILURE,
            $event->getRequest()->getClientIp()
        );

        if ($lock && $lock->getValue() > self::BAN_AFTER_ATTEMPTS) {
            $event->setResponse(new Response($this->twig->render('pages/misc/block.html.twig')));
        }
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $username = $event->getAuthenticationToken()->getUsername();
        $ip = $this->request->getCurrentRequest()->getClientIp();

        $this->updateLock(self::LOGIN_ATTEMPT_FAILURE, $ip);

        $this->logger->warning(sprintf('Login attempt failure with email %s from remote address %s', $username, $ip));
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        $user->setLastLogin(DateTimeService::getCurrentUTC());
        $user->setIp($this->request->getCurrentRequest()->getClientIp());

        $this->em->flush();

        $this->logger->warning(sprintf('Open session for user %s', $user->getEmail()));
    }

    /**
     * @throws \Exception
     */
    public function updateLock(string $lockName, string $ip): void
    {
        $interval = new \DateInterval('PT1H');
        $expire = DateTimeService::getCurrentUTC()->add($interval);

        $lock = $this->em->getRepository(Lock::class)->getLock($lockName, $ip);
        if ($lock) {
            $lock->setValue($lock->getValue() + 1);
            $lock->setExpire($expire);
            $this->em->flush();

            return;
        }

        $lock = new Lock($lockName);
        $lock->setData($ip);
        $lock->setValue(1);
        $lock->setExpire($expire);
        $this->em->persist($lock);

        $this->em->flush();
    }
}
