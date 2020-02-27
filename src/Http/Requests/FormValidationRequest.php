<?php

namespace Vuravel\Form\Http\Requests;

use Vuravel\Core\Http\Requests\SessionAuthorizationRequest;

class FormValidationRequest extends SessionAuthorizationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->object->getValidationRules();
    }
}
