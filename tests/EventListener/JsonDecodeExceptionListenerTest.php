<?php

namespace Adamsafr\FormRequestBundle\Tests\EventListener;

use Adamsafr\FormRequestBundle\EventListener\JsonDecodeExceptionListener;
use Adamsafr\FormRequestBundle\Exception\JsonDecodeException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonDecodeExceptionListenerTest extends TestCase
{
    use EventExceptionResponseTrait;

    public function testConvertsExceptionResult()
    {
        $listener = new JsonDecodeExceptionListener();
        $event = $this->createEventMock(new JsonDecodeException(), true);

        $event->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->once())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnNotMasterRequest()
    {
        $listener = new JsonDecodeExceptionListener();
        $event = $this->createEventMock(new JsonDecodeException(), false);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }

    public function testOnMasterRequestWithOtherException()
    {
        $listener = new JsonDecodeExceptionListener();
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
        $listener = new JsonDecodeExceptionListener();
        $event = $this->createEventMock(new BadRequestHttpException(), false);

        $event->expects($this->never())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $event->expects($this->never())
            ->method('stopPropagation');

        $listener->onKernelException($event);
    }
}
