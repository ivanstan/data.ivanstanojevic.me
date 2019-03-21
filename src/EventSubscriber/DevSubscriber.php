<?php

namespace App\EventSubscriber;

use App\Kernel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DevSubscriber implements EventSubscriberInterface
{
    private $env;

    private static $messages = ['info', 'warning', 'danger', 'success'];

    public function __construct($env)
    {
        $this->env = $env;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (Kernel::DEV !== $this->env) {
            return;
        }

        foreach (self::$messages as $key) {
            $type = $event->getRequest()->query->get($key);

            if ($type !== null) {
                /** @var Session $session */
                $session = $event->getRequest()->getSession();

                $session
                    ->getFlashBag()
                    ->add($key, 'This is a sample message. It\'s only available in dev environment.');
            }
        }
    }
}
