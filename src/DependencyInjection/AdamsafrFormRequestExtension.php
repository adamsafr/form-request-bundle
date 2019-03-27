<?php

namespace Adamsafr\FormRequestBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AdamsafrFormRequestExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $this->loadExceptionListeners($loader, $config);

        $definition = $container->getDefinition('adamsafr_form_request.controller_request_resolver');
        $definition->setArgument(2, $config['request']['decode_json_body']);
    }

    private function loadExceptionListeners(LoaderInterface $loader, array $config): void
    {
        $listeners = $config['exception_listeners'];

        if ($listeners['access_denied']['enabled']) {
            $loader->load('access_denied_exception_listener.xml');
        }

        if ($listeners['form_validation']['enabled']) {
            $loader->load('form_validation_exception_listener.xml');
        }

        if ($listeners['json_decode']['enabled']) {
            $loader->load('json_decode_exception_listener.xml');
        }
    }
}
