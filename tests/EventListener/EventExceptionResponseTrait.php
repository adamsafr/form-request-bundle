<?php

namespace Adamsafr\FormRequestBundle\Tests\EventListener;

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
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $event
            ->expects($this->any())
            ->method('isMasterRequest')
            ->will($this->returnValue($masterRequest))
        ;
        $event
            ->expects($this->any())
            ->method('getException')
            ->will($this->returnValue($exception))
        ;

        return $event;
    }
}
