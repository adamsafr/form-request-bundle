<?php

namespace Adamsafr\FormRequestBundle\DependencyInjection\Compiler;

use Adamsafr\FormRequestBundle\Request\FormRequest;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class FormRequestPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('adamsafr_form_request.form_request_service_locator')) {
            return;
        }

        $locator = $container->getDefinition('adamsafr_form_request.form_request_service_locator');

        $taggedServices = $container->findTaggedServiceIds('adamsafr_form_request.form_request');
        $references = [];

        foreach ($taggedServices as $id => $tags) {
            if ($id === FormRequest::class) {
                continue;
            }

            $references[$id] = new Reference($id);
        }

        $locator->setArguments([$references]);
    }
}
