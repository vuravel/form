<?php

namespace Vuravel\Form;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Vuravel\Catalog\Components\Traits\DoesSorting;
use \Vuravel\Catalog\Components\Traits\FiltersCatalog;
use Vuravel\Form\Component;

class Field extends Component
{
    use Traits\LabelInfoComment;
    use Traits\RelatesToFormSubmission;
    use DoesSorting, FiltersCatalog;
    use Traits\EloquentField;

    public $menuComponent = 'Field';


    protected $defaultTrigger = 'change';

    /**
     * The field's HTML attribute in the form (also the formData key).
     *
     * @var string
     */
    public $name;
    
    /**
     * The field's value.
     *
     * @var string|array
     */
    public $value;

    /**
     * The field's placeholder.
     *
     * @var string|array
     */
    public $placeholder;

    /**
     * The field's validation rules.
     *
     * @var string
     */
    public $rules = '';

    /**
     * The field's sluggable column.
     *
     * @var string|false
     */
    protected $slug = false;

    /**
     * Initializes a Field component.
     *
     * @param  string $label
     * @return void
     */
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->name = Str::snake($label); //not $this->label because it could be already translated

        $this->data(['displayIdAttribute' => true]);

    }

    /**
     * Passes Form attributes to the component and sets it's value if it is a Field.
     *
     * @param  Vuravel\Form\Form $form
     * @return void
     */
    public function prepareComponent($form)
    {
        if($this->rules)
            $form->addValidationRules([$this->name => $this->rules ]);

        if($form->record) //If it's an Eloquent Field...
            $this->setValueFromDB($form->record);

        $this->checkSetReadonly($form);

        $this->mountedHook($form);
    }

    /**
     * Checks authorization and sets a readonly field if necessary.
     *
     * @param     \VlForm  $form   The form Class
     */
    protected function checkSetReadonly($form)
    {
        if(method_exists($form, 'authorize')){

            $authorization = $form->authorize();
            
            if(!$authorization || (is_array($authorization) && !in_array($this->name, $authorization)))
                $this->readOnly();
        }
    }

    /**
     * Sets the name attribute manually for the field. 
     * This is the FormData key for the input.
     *
     * @param  string $name The name attribute of the field.
     * 
     * @return self
     */
    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the value of the field. 
     * <u>Note</u>: if the Form is connected to an Eloquent Model, the DB value takes precedence.
     *
     * @param  string|array $value The value to be set.
     * @return self
     */
    public function value($value)
    {
        $this->setValue($value);
        return $this;
    }

    /**
     * Sets the value directly.
     *
     * @param  string|array $value
     * @return void
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Sets the placeholder of this field. 
     * By default, the fields have no placeholder.
     *
     * @param  string $placeholder The placeholder for the field.
     * @return self
     */
    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Sets a default value to the field. Applies if the value is empty.
     *
     * @param  string $defaultValue The default value
     * @return self
     */
    public function default($defaultValue)
    {
        if($this->pristine())
            $this->setValue($defaultValue);
        return $this;
    }

    /**
     * Determines if the field has a value or is pristine.
     *
     * @return Boolean
     */
    public function pristine()
    {
        return $this->value ? false : true;
    }

    /**
     * Adds a slug in the table from this field. For example, this will populate the `title` column with the field's value and the `slug` column with it's corresponding slug. 
     * <php>Input::form('Title')->sluggable('slug')</php>
     *
     * @param  string|null $slugColumn The name of the column that contains the slug
     * @return self
     */
    public function sluggable($slugColumn = 'slug')
    {
        $this->slug = $slugColumn;
        return $this;
    }

    /**
     * Appends <a href="https://laravel.com/docs/master/validation#available-validation-rules" target="_blank">Laravel input validation rules</a> for the field.
     *
     * @param  string|array $rules A | separated string of validation rules or Array of rules.
     * @return self
     */
    public function rules($rules)
    {
        $this->rules .= '|'.(is_array($rules) ? implode('|', $rules) : $rules);
        $this->rules = ltrim($this->rules, '|');
        return $this;
    }

    /**
     * Sets a required (&#42;) indicator and adds a required validation rule to the field.
     * 
     * @param string|null The required indicator Html. The default is (&#42;).
     *
     * @return self
     */
    public function required($indicator = '*')
    {
        $this->data(['required' => $indicator]);
        $this->rules('required');
        return $this;
    }

    /**
     * Makes the field readonly (not editable).
     *
     * @return self
     */
    public function readOnly()
    {
        return $this->data(['readOnly' => true]);
    }

    /**
     * Checks if the field is readonly (not editable).
     *
     * @return Boolean
     */
    protected function isReadOnly()
    {
        return $this->data('readOnly');
    }

    /**
     * Removes the browser's default autocomplete behavior from the field.
     *
     * @return self
     */
    public function noAutocomplete()
    {
        $this->data(['noAutocomplete' => true]);
        return $this;
    }

}