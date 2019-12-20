<?php

namespace Adamsafr\FormRequestBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Kernel;

class AccessDeniedExceptionListener
{
    public function onKernelException($event): void
    {
        if (Kernel::VERSION >= 4.3 && class_exists('Symfony\Component\HttpKernel\Event\ExceptionEvent')) {
            $this->handleEvent($event);
        } else {
            $this->handleLegacyEvent($event);
        }
    }

    private function handleLegacyEvent(GetResponseForExceptionEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $exception = $event->getException();

        if (!$exception instanceof AccessDeniedHttpException) {
            return;
        }

        $event->setResponse($this->prepareResponse($exception));
        $event->stopPropagation();
    }

    private function handleEvent(ExceptionEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $exception = $event->getThrowable();

        if (!$exception instanceof AccessDeniedHttpException) {
            return;
        }

        $event->setResponse($this->prepareResponse($exception));
    }

    private function prepareResponse(AccessDeniedHttpException $exception): JsonResponse
    {
        return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
    }
}
