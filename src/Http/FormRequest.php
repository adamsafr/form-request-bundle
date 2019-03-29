<?php

namespace Adamsafr\FormRequestBundle\Http;

use Symfony\Component\Validator\Constraint;

class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return null|Constraint|Constraint[]
     */
    public function rules()
    {
        return null;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        return $this->all();
    }

    /**
     * The validation groups to validate.
     *
     * @return array
     */
    public function validationGroups(): array
    {
        return [];
    }
}
