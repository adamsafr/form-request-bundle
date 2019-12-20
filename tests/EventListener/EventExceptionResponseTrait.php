<?php

namespace Adamsafr\FormRequestBundle\Tests\EventListener;

use Symfony\Component\HttpKernel\Kernel;

/**
 * @mixin \PHPUnit\Framework\TestCase
 */
trait EventExceptionResponseTrait
{
    /**
     * @param \Exception $exception
     * @param bool $masterRequest
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent
     */
    private function createEventMock(\Exception $exception, bool $masterRequest = true)
    {
        $event = $this
            ->getMockBuilder($this->getExceptionEventClassName())
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $event
            ->expects($this->any())
            ->method(Kernel::VERSION >= 4.3 ? 'getThrowable' : 'getException')
            ->will($this->returnValue($exception))
        ;
        $event
            ->expects($this->any())
            ->method('isMasterRequest')
            ->will($this->returnValue($masterRequest))
        ;

        return $event;
    }

    private function getExceptionEventClassName(): string
    {
        if (Kernel::VERSION >= 4.3 && class_exists('Symfony\Component\HttpKernel\Event\ExceptionEvent')) {
            return 'Symfony\Component\HttpKernel\Event\ExceptionEvent';
        }

        return 'Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent';
    }
}
