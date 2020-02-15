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
    public function fillModelFromRequest($request, $record)
    {
        collect($this->getFieldComponents())->each(function($field) use($request, $record) {

            $field->fillBeforeSave($request, $record);
            
        });
    }

    /**
     * Saves the relations of the Eloquent model from the request data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function assignRelationsFromRequest($request, $record)
    {
        collect($this->getFieldComponents())->each(function($field) use($request, $record){
            
            $field->fillAfterSave($request, $record);
            
        });

        $record->refresh($this->relationsArray());
    }

    public function getFieldComponents()
    {
        return collect($this->components)->flatMap( function($component) {

            return $component->getFieldComponents();

        })->filter();
    }

    public function relationsArray()
    {
        return collect($this->getFieldComponents())->map(function($field){
            
            return $field->getRelationName();
        
        })->filter()->values()->all();
    }
}