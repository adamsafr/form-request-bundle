<?php

namespace Adamsafr\FormRequestBundle;

use Adamsafr\FormRequestBundle\DependencyInjection\Compiler\FormRequestPass;
use Adamsafr\FormRequestBundle\Http\FormRequest;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdamsafrFormRequestBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerForAutoconfiguration(FormRequest::class)
            ->addTag('adamsafr_form_request.form_request');

        $container->addCompilerPass(new FormRequestPass());
    }
}
