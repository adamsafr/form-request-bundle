<?php

namespace Adamsafr\FormRequestBundle\EventListener;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Service\ValidationErrorsTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

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

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $exception = $event->getException();

        if (!$exception instanceof FormValidationException) {
            return;
        }

        $errors = $this->transformer->transform($exception->getViolations());

        $response = new JsonResponse([
            'message' => $exception->getMessage(),
            'errors' => $errors,
        ], JsonResponse::HTTP_BAD_REQUEST);

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
