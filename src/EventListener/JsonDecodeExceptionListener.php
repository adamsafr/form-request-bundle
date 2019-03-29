<?php

namespace Adamsafr\FormRequestBundle\EventListener;

use Adamsafr\FormRequestBundle\Exception\JsonDecodeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class JsonDecodeExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $exception = $event->getException();

        if (!$exception instanceof JsonDecodeException) {
            return;
        }

        $response = new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
