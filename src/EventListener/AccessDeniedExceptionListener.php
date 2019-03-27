<?php

namespace Adamsafr\FormRequestBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $exception = $event->getException();

        if (!$exception instanceof AccessDeniedHttpException) {
            return;
        }

        $response = new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
