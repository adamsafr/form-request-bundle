<?php

namespace Adamsafr\FormRequestBundle\Tests\EventListener;

use Adamsafr\FormRequestBundle\EventListener\AccessDeniedExceptionListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedExceptionListenerTest extends TestCase
{
    use EventExceptionResponseTrait;

    public function testConvertsExceptionResult()
    {
        $listener = new AccessDeniedExceptionListener();
        $event = $this->createEventMock(new AccessDeniedHttpException(), true);

        $event->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->once())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnNotMasterRequest()
    {
        $listener = new AccessDeniedExceptionListener();
        $event = $this->createEventMock(new AccessDeniedHttpException(), false);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnMasterRequestWithOtherException()
    {
        $listener = new AccessDeniedExceptionListener();
        $event = $this->createEventMock(new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException(), true);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnNotMasterRequestWithOtherException()
    {
        $listener = new AccessDeniedExceptionListener();
        $event = $this->createEventMock(new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException(), false);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }
}
