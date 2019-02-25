<?php

namespace App\EventSubscriber;

use App\Controller\SecurityController;
use App\Entity\User;
use App\Service\Traits\TranslatorAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ForceUserPasswordSubscriber implements EventSubscriberInterface
{
    use TranslatorAwareTrait;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $urlGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user && $user->getPassword() === null) {
            if ($this->getControllerName($event) !== SecurityController::class && $event->getRequest()->getMethod() !== 'settings') {
                $event->getRequest()->getSession()->getFlashBag()->add('info',
                    $this->translator->trans('settings.password_is_null')
                );

                $redirectUrl = $this->urlGenerator->generate('security_settings');
                $event->setController(function () use ($redirectUrl) {
                    return new RedirectResponse($redirectUrl);
                });
            }
        }
    }

    private function getControllerName(FilterControllerEvent $event): string
    {
        $controller = $event->getController()[0] ?? null;

        if (!$controller || !is_object($controller)) {
            return null;
        }

        return get_class($controller);
    }
}
