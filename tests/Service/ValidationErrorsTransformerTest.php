<?php

namespace Adamsafr\FormRequestBundle\Tests\Service;

use Adamsafr\FormRequestBundle\Service\ValidationErrorsTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Tests\Fixtures\FakeMetadataFactory;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Translation\IdentityTranslator;

class ValidationErrorsTransformerTest extends TestCase
{
    public function testWithEmptyParameters()
    {
        $body = [
            'profile' => [
                'address' => [],
            ],
        ];

        $request = Request::create('/', 'POST', $body);
        $constraints = $this->createValidator()->validate($request->request->all(), $this->getAsserts());

        $expected = [
            'profile' => [
                'email' => 'This field is missing.',
                'firstName' => 'This field is missing.',
                'lastName' => 'This field is missing.',
                'address' => [
                    'country' => 'This field is missing.',
                    'city' => 'This field is missing.',
                    'street' => 'This field is missing.',
                ],
            ],
        ];

        $this->assertEquals($expected, (new ValidationErrorsTransformer())->transform($constraints));
    }

    public function testWithSeveralErrorsForOneField()
    {
        $body = [
            'profile' => [
                'email' => 'inv',
                'address' => [
                    'street' => [
                        'name' => 'Invalid street name',
                    ],
                ],
            ],
        ];

        $request = Request::create('/', 'POST', $body);
        $constraints = $this->createValidator()->validate($request->request->all(), $this->getAsserts());

        $expected = [
            'profile' =>  [
                'email' => [
                    'This value is not a valid email address.',
                    'This value is too short. It should have 5 characters or more.',
                ],
                'firstName' => 'This field is missing.',
                'lastName' => 'This field is missing.',
                'address' => [
                    'country' => 'This field is missing.',
                    'city' => 'This field is missing.',
                    'street' =>  [
                        'name' =>  [
                            'This value is too long. It should have 10 characters or less.',
                            'This value should not be equal to "Invalid street name".',
                        ],
                        'number' => 'This field is missing.',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, (new ValidationErrorsTransformer())->transform($constraints));
    }

    private function createValidator(): RecursiveValidator
    {
        $translator = new IdentityTranslator();
        $translator->setLocale('en');

        return new RecursiveValidator(
            new ExecutionContextFactory($translator),
            new FakeMetadataFactory(),
            new ConstraintValidatorFactory()
        );
    }

    private function getAsserts(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'profile' => new Assert\Collection([
                    'email' => [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                        new Assert\Length(['min' => 5]),
                    ],
                    'firstName' => new Assert\NotBlank(),
                    'lastName' => new Assert\NotBlank(),
                    'address' => new Assert\Collection([
                        'country' => [
                            new Assert\NotBlank(),
                        ],
                        'city' => new Assert\NotBlank(),
                        'street' => new Assert\Collection([
                            'name' => [
                                new Assert\NotBlank(),
                                new Assert\Length(['max' => 10]),
                                new Assert\NotEqualTo('Invalid street name'),
                            ],
                            'number' => new Assert\NotBlank(),
                        ]),
                    ]),
                ]),
            ],
        ]);
    }
}
