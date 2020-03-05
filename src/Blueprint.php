<?php
namespace Vuravel\Form;

class Blueprint
{
    /**
     * The form's components.
     *
     * @var array
     */
    public $components;

    /**
     * Constructs a Vuravel\Form\Eloquent object
     *
     * @param  array $components
     * @return void
     */
    public function __construct($components)
    {
		$this->components = $components;
    }

    /**
     * Prepare the components' attributes and values.
     *
     * @param  array  $components
     * @return void
     */
    public function getPreparedComponents($form)
    {
        return $this->components = collect($this->components)->filter()->each( function($component) use ($form) {
            $component->prepareComponent($form);

        })->values()->all();
    }

    /**
     * Fills an Eloquent model from the request data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function fillModelFromRequest($request, $form)
    {
        collect($this->getFieldComponents($form))->each(function($field) use ($request, $form) {

            $field->fillBeforeSave($request, $form->record);
            
        });
    }

    /**
     * Saves the relations of the Eloquent model from the request data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function assignRelationsFromRequest($request, $form)
    {
        collect($this->getFieldComponents($form))->each(function($field) use ($request, $form){
            
            $field->fillAfterSave($request, $form->record);
            
        });

        $form->record->refresh($this->relationsArray($form));
    }

    public function getFieldComponents($form)
    {
        return collect($this->components)->flatMap( function($component) use ($form) {

            return $component->getFieldComponents($form);

        })->filter();
    }

    public function relationsArray($form)
    {
        return collect($this->getFieldComponents($form))->map(function($field){
            
            return $field->getRelationName();
        
        })->filter()->values()->all();
    }
}