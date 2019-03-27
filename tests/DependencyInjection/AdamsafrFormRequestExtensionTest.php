<?php

namespace Adamsafr\FormRequestBundle\Tests\DependencyInjection;

use Adamsafr\FormRequestBundle\DependencyInjection\AdamsafrFormRequestExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdamsafrFormRequestExtensionTest extends TestCase
{
    public function testWithoutConfigFile()
    {
        $container = new ContainerBuilder();

        $extension = new AdamsafrFormRequestExtension();
        $config = [];

        $extension->load([$config], $container);

        $this->assertFalse($container->has('adamsafr_form_request.access_denied_exception_listener'));
        $this->assertFalse($container->has('adamsafr_form_request.form_validation_exception_listener'));
        $this->assertFalse($container->has('adamsafr_form_request.json_decode_exception_listener'));

        $definition = $container->getDefinition('adamsafr_form_request.controller_request_resolver');
        $this->assertFalse($definition->getArgument(2));
    }

    public function testWithDefaultConfigFile()
    {
        $container = new ContainerBuilder();

        $extension = new AdamsafrFormRequestExtension();
        $config = [
            'exception_listeners' => [
                'access_denied' => [
                    'enabled' => false,
                ],
                'form_validation' => [
                    'enabled' => false,
                ],
                'json_decode' => [
                    'enabled' => false,
                ],
            ],
            'request' => [
                'decode_json_body' => false,
            ],
        ];

        $extension->load([$config], $container);

        $this->assertFalse($container->has('adamsafr_form_request.access_denied_exception_listener'));
        $this->assertFalse($container->has('adamsafr_form_request.form_validation_exception_listener'));
        $this->assertFalse($container->has('adamsafr_form_request.json_decode_exception_listener'));

        $definition = $container->getDefinition('adamsafr_form_request.controller_request_resolver');
        $this->assertFalse($definition->getArgument(2));
    }

    public function testConfigFileWithAllEnabledOptions()
    {
        $container = new ContainerBuilder();

        $extension = new AdamsafrFormRequestExtension();
        $config = [
            'exception_listeners' => [
                'access_denied' => [
                    'enabled' => true,
                ],
                'form_validation' => [
                    'enabled' => true,
                ],
                'json_decode' => [
                    'enabled' => true,
                ],
            ],
            'request' => [
                'decode_json_body' => true,
            ],
        ];

        $extension->load([$config], $container);

        $this->assertTrue($container->has('adamsafr_form_request.access_denied_exception_listener'));
        $this->assertTrue($container->has('adamsafr_form_request.form_validation_exception_listener'));
        $this->assertTrue($container->has('adamsafr_form_request.json_decode_exception_listener'));

        $definition = $container->getDefinition('adamsafr_form_request.controller_request_resolver');
        $this->assertTrue($definition->getArgument(2));
    }
}
