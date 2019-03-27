<?php

namespace Adamsafr\FormRequestBundle\Tests\EventListener;

use Adamsafr\FormRequestBundle\EventListener\FormValidationExceptionListener;
use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Service\ValidationErrorsTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormValidationExceptionListenerTest extends TestCase
{
    use EventExceptionResponseTrait;

    public function testConvertsExceptionResult()
    {
        $listener = new FormValidationExceptionListener(new ValidationErrorsTransformer());
        $exception = $this->getFormValidationExceptionMock();

        $exception->expects($this->once())
            ->method('getViolations');

        $event = $this->createEventMock($exception, true);

        $event->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->once())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnNotMasterRequest()
    {
        $listener = new FormValidationExceptionListener(new ValidationErrorsTransformer());
        $exception = $this->getFormValidationExceptionMock();

        $exception->expects($this->never())
            ->method('getViolations');

        $event = $this->createEventMock($exception, false);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnMasterRequestWithOtherException()
    {
        $listener = new FormValidationExceptionListener(new ValidationErrorsTransformer());
        $event = $this->createEventMock(new BadRequestHttpException(), true);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnNotMasterRequestWithOtherException()
    {
        $listener = new FormValidationExceptionListener(new ValidationErrorsTransformer());
        $event = $this->createEventMock(new BadRequestHttpException(), false);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|FormValidationException
     */
    private function getFormValidationExceptionMock()
    {
        $exception = $this
            ->getMockBuilder(FormValidationException::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $exception;
    }
}
