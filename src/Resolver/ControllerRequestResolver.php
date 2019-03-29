<?php

namespace Adamsafr\FormRequestBundle\Resolver;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use Adamsafr\FormRequestBundle\Http\FormRequest;
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


    public function __construct(ContainerInterface $locator, ValidatorInterface $validator)
    {
        $this->locator = $locator;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     * @throws FormValidationException
     * @throws AccessDeniedHttpException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $form = $this->initializeRequest($request, $argument);

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

    /**
     * Return prepared validation groups.
     *
     * @param FormRequest $form
     * @return Assert\GroupSequence|null
     */
    private function prepareValidationGroups(FormRequest $form): ?Assert\GroupSequence
    {
        $groups = $form->validationGroups();

        return !empty($groups) ? new Assert\GroupSequence($groups) : null;
    }

    /**
     * Initialize the form request with data from the given request.
     *
     * @param Request $current
     * @param ArgumentMetadata $argument
     * @return FormRequest
     */
    private function initializeRequest(Request $current, ArgumentMetadata $argument): FormRequest
    {
        $form = $this->locator->get($argument->getType());

        if (!$form instanceof FormRequest) {
            throw new \LogicException(sprintf('$form is not instance of %s', FormRequest::class));
        }

        $files = $current->files->all();

        $files = is_array($files) ? array_filter($files) : $files;

        $newRequest = new Request();

        $newRequest->initialize(
            $current->query->all(), $current->request->all(), $current->attributes->all(),
            $current->cookies->all(), $files, $current->server->all(), $current->getContent()
        );

        $form->setRequest($newRequest);
        $form->setJson($form->json());

        if ($form->isJson()) {
            $newRequest->request->replace($form->json()->all());
        }

        return $form;
    }
}
