<?php

namespace Adamsafr\FormRequestBundle\Resolver;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Request\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ControllerRequestResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $locator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var bool
     */
    private $replaceOriginalRequestByJson;


    public function __construct(
        ContainerInterface $locator,
        ValidatorInterface $validator,
        bool $replaceOriginalRequestByJson = false
    ) {
        $this->locator = $locator;
        $this->validator = $validator;
        $this->replaceOriginalRequestByJson = $replaceOriginalRequestByJson;
    }

    /**
     * {@inheritdoc}
     * @throws FormValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $form = $this->locator->get($argument->getType());

        if (!$form instanceof FormRequest) {
            throw new \LogicException(sprintf('$form is not instance of %s', FormRequest::class));
        }

        $form->setHttpRequest($request);

        if ($this->replaceOriginalRequestByJson && $form->isJson()) {
            $request->request->replace($form->json()->all());
        }

        if (!$form->authorize()) {
            throw new AccessDeniedHttpException('Access denied.');
        }

        $violations = $this->validator->validate(
            $form->validationData(),
            $form->rules(),
            $this->prepareValidationGroups($form)
        );

        if ($violations->count() > 0) {
            throw new FormValidationException($violations, 'Validation error.');
        }

        yield $form;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return (new \ReflectionClass($argument->getType()))->isSubclassOf(FormRequest::class);
    }

    private function prepareValidationGroups(FormRequest $form): ?Assert\GroupSequence
    {
        $groups = $form->validationGroups();

        return !empty($groups) ? new Assert\GroupSequence($groups) : null;
    }
}
