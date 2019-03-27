<?php

namespace Adamsafr\FormRequestBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;

class FormRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): ?Assert\Collection
    {
        return null;
    }

    public function validationData(): array
    {
        return $this->all();
    }

    public function validationGroups(): ?array
    {
        return null;
    }
}
