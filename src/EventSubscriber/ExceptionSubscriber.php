<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {
            $this->setResponse($event, Response::HTTP_NOT_FOUND, $exception->getMessage());
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $this->setResponse($event, Response::HTTP_INTERNAL_SERVER_ERROR, 'Server error');
        }
    }

    private function setResponse(GetResponseForExceptionEvent $event, int $code, string $message): void
    {
        $response = new JsonResponse(
            [
                'response' => [
                    'code' => $code,
                    'message' => $message,
                ],
            ]
        );

        $event->setResponse($response);
    }
}
