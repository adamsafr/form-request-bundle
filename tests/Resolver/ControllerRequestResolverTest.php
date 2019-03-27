<?php

namespace Adamsafr\FormRequestBundle\Tests\Resolver;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Locator\FormRequestServiceLocator;
use Adamsafr\FormRequestBundle\Request\FormRequest;
use Adamsafr\FormRequestBundle\Resolver\ControllerRequestResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class ControllerRequestResolverTest extends TestCase
{
    public function testWithValidRequestData()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|RecursiveValidator $validator */
        $validator = $this
            ->getMockBuilder(RecursiveValidator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $validator->expects($this->any())
            ->method('validate')
            ->willReturn(new ConstraintViolationList())
        ;

        $resolver = new ControllerRequestResolver(
            new FormRequestServiceLocator([
                TestRequest::class => function () {
                    return new TestRequest();
                },
            ]),
            $validator,
            false
        );

        $request = Request::create('/');
        $argument = new ArgumentMetadata('testRequest', TestRequest::class, false, false, null);

        $testRequest = new TestRequest();
        $testRequest->setSymfonyRequest($request);

        $this->assertTrue($resolver->supports($request, $argument));
        $this->assertYieldEquals([$testRequest], $resolver->resolve($request, $argument));
    }

    public function testWithBadRequestData()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|ConstraintViolationList $constraints */
        $constraints = $this
            ->getMockBuilder(ConstraintViolationList::class)
            ->getMock()
        ;
        $constraints->expects($this->any())
            ->method('count')
            ->willReturn(1)
        ;

        /** @var \PHPUnit\Framework\MockObject\MockObject|RecursiveValidator $validator */
        $validator = $this
            ->getMockBuilder(RecursiveValidator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $validator->expects($this->any())
            ->method('validate')
            ->willReturn($constraints)
        ;

        $resolver = new ControllerRequestResolver(
            new FormRequestServiceLocator([
                TestRequest::class => function () {
                    return new TestRequest();
                },
            ]),
            $validator,
            false
        );

        $request = Request::create('/');
        $argument = new ArgumentMetadata('testRequest', TestRequest::class, false, false, null);

        $testRequest = new TestRequest();

        $this->assertTrue($resolver->supports($request, $argument));
        $this->expectException(FormValidationException::class);
        $this->assertYieldEquals([$testRequest], $resolver->resolve($request, $argument));
    }

    public function testNotAuthorizedRequest()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|RecursiveValidator $validator */
        $validator = $this
            ->getMockBuilder(RecursiveValidator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $resolver = new ControllerRequestResolver(
            new FormRequestServiceLocator([
                TestRequest::class => function () {
                    return $this->getNotAuthorizedTestRequestMock();
                },
            ]),
            $validator,
            false
        );

        $request = Request::create('/');
        $argument = new ArgumentMetadata('testRequest', TestRequest::class, false, false, null);

        $this->assertTrue($resolver->supports($request, $argument));
        $this->expectException(AccessDeniedHttpException::class);
        $this->assertYieldEquals([$this->getNotAuthorizedTestRequestMock()], $resolver->resolve($request, $argument));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|TestRequest
     */
    private function getNotAuthorizedTestRequestMock()
    {
        $testRequest = $this->getMockBuilder(TestRequest::class)->getMock();

        $testRequest->expects($this->any())
            ->method('authorize')
            ->willReturn(false);

        return $testRequest;
    }

    private function assertYieldEquals(array $expected, \Generator $generator)
    {
        $args = [];
        foreach ($generator as $arg) {
            $args[] = $arg;
        }

        $this->assertEquals($expected, $args);
    }
}

class TestRequest extends FormRequest
{
}
