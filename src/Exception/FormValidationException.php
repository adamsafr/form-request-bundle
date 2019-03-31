<?php

namespace Adamsafr\FormRequestBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class FormValidationException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violations;


    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Bad request.',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
