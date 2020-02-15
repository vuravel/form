<?php 
namespace Vuravel\Form\Traits;

use Illuminate\Support\Arr;

trait HasValidationRules {

    protected $validationRules = [];

    public function getValidationRules()
    {
    	return $this->validationRules;
    }

    public function addValidationRules($rules)
    {
        foreach ($rules as $key => $value) {
            $existingRules = Arr::get($this->validationRules, $key);
            $this->validationRules[$key] = ltrim((is_array($existingRules) ? implode('|', $existingRules) : $existingRules).'|'.$value, '|');
        }
    	return $this;
    }

}