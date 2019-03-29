<?php

namespace Adamsafr\FormRequestBundle\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorsTransformer
{
    /**
     * Return array representation of the ConstraintViolationListInterface.
     *
     * @param ConstraintViolationListInterface $violations
     * @return array
     */
    public function transform(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            if ($this->hasNestedProperties($violation)) {
                $errors = array_merge_recursive($errors, $this->buildNestedErrorTree($violation));
            } else {
                $errors[$this->getPreparedPropertyPath($violation)] = $violation->getMessage();
            }
        }

        return $errors;
    }

    /**
     * Check if given violation has child error fields.
     *
     * @param ConstraintViolationInterface $violation
     * @return bool
     */
    private function hasNestedProperties(ConstraintViolationInterface $violation): bool
    {
        return mb_strpos($violation->getPropertyPath(), '][') !== false;
    }

    /**
     * Build tree with child error fields.
     *
     * @param ConstraintViolationInterface $violation
     * @return array
     */
    private function buildNestedErrorTree(ConstraintViolationInterface $violation): array
    {
        $paths = explode('][', $violation->getPropertyPath());

        $paths = array_map(function ($value) {
            return str_replace(['[', ']'], '', $value);
        }, $paths);

        $output = $violation->getMessage();

        foreach (array_reverse($paths) as $value) {
            $output = [$value => $output];
        }

        return $output;
    }

    /**
     * Get field name between square braces.
     *
     * @param ConstraintViolationInterface $violation
     * @return string
     */
    private function getPreparedPropertyPath(ConstraintViolationInterface $violation): string
    {
        if (preg_match('/\[(.*?)\]/', $violation->getPropertyPath(), $result)) {
            return $result[1];
        }

        return $violation->getPropertyPath();
    }
}
