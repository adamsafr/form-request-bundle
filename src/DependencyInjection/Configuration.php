<?php

namespace Adamsafr\FormRequestBundle\DependencyInjection;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Exception\JsonDecodeException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder  = new TreeBuilder('adamsafr_form_request');
        // BC layer for symfony/config < 4.2
        $rootNode = method_exists($treeBuilder, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root('adamsafr_form_request');

        $rootNode
            ->children()
                ->arrayNode('exception_listeners')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('access_denied')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultFalse()
                                    ->info(sprintf('Sets json response of the %s', AccessDeniedHttpException::class))
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('form_validation')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultFalse()
                                    ->info(sprintf('Sets json response of the %s', FormValidationException::class))
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('json_decode')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultFalse()
                                    ->info(sprintf('Sets json response of the %s', JsonDecodeException::class))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
