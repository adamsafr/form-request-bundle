<?php

namespace Adamsafr\FormRequestBundle\EventListener;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Service\ValidationErrorsTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Kernel;

class FormValidationExceptionListener
{
    /**
     * @var ValidationErrorsTransformer
     */
    private $transformer;


    public function __construct(ValidationErrorsTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

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

        if (!$exception instanceof FormValidationException) {
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

        if (!$exception instanceof FormValidationException) {
            return;
        }

        $event->setResponse($this->prepareResponse($exception));
    }

    private function prepareResponse(FormValidationException $exception): JsonResponse
    {
        $errors = $this->transformer->transform($exception->getViolations());

        return new JsonResponse([
            'message' => $exception->getMessage(),
            'errors' => $errors,
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
