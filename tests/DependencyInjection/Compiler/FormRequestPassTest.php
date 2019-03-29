<?php

namespace Adamsafr\FormRequestBundle\Tests\DependencyInjection\Compiler;

use Adamsafr\FormRequestBundle\DependencyInjection\Compiler\FormRequestPass;
use Adamsafr\FormRequestBundle\Http\FormRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class FormRequestPassTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Definition
     */
    private $serviceLocatorDefinition;

    /**
     * @var Definition
     */
    private $formRequestDefinition;


    protected function setUp()
    {
        parent::setUp();

        $this->container = new ContainerBuilder();

        $this->serviceLocatorDefinition = new Definition();
        $this->container->setDefinition(
            'adamsafr_form_request.form_request_service_locator',
            $this->serviceLocatorDefinition
        );

        $this->formRequestDefinition = new Definition(FormRequest::class);
        $this->formRequestDefinition->setTags([
            'adamsafr_form_request.form_request' => [
                [],
            ],
        ]);
    }

    public function testWithNoTaggedFormRequests()
    {
        $pass = new FormRequestPass();
        $pass->process($this->container);

        $this->assertEquals(1, count($this->serviceLocatorDefinition->getArguments()));
        $this->assertEquals([[]], $this->serviceLocatorDefinition->getArguments());
    }

    public function testWithTaggedRequests()
    {
        $this->container->setDefinition(TestPassRequest1::class, $this->formRequestDefinition);
        $this->container->setDefinition(TestPassRequest2::class, $this->formRequestDefinition);

        $pass = new FormRequestPass();
        $pass->process($this->container);

        $expected = [
            [
                TestPassRequest1::class => new Reference(TestPassRequest1::class),
                TestPassRequest2::class => new Reference(TestPassRequest2::class),
            ],
        ];

        $this->assertEquals(1, count($this->serviceLocatorDefinition->getArguments()));
        $this->assertEquals($expected, $this->serviceLocatorDefinition->getArguments());
    }
}

class TestPassRequest1 extends FormRequest
{

}

class TestPassRequest2 extends FormRequest
{

}
