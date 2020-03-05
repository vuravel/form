<?php 
namespace Vuravel\Form\Traits;

use Schema;
use Vuravel\Form\Model;

trait EloquentField {

    /**
     * The relation's selected array of values to be sent to the Front-End Form.
     *
     * @var array
     */
    protected $select = [];

    /**
     * Is the field an attribute? Yes, by default.
     *
     * @var boolean
     */
    protected $isAttribute = true;

    /**
     * Does the field fill the record before save? For attributes and belongsTo relationship
     *
     * @var boolean
     */
    protected $fillsBeforeSave = true;

    /**
     * Does the field fill the record's relations after save? For all relationships (including belongsTo)
     *
     * @var boolean
     */
    protected $fillsAfterSave = false;

    /**
     * If the field has nothing to do with the model (ex: superfluous Front-End field, password confirmation, etc...), set to true.
     *
     * @var boolean
     */
    protected $notRelatedToModel = false;

    /**
     * If the field updates an attribute on the HasOne relationship.
     *
     * @var string
     */
    protected $forHasOne;

    /**
     * The attribute to fill when the field represents to an Eloquent relationship.
     *
     * @var  string
     */
    protected $feedsColumn;

    /**
     * Additional attributes to fill when saving the model's attribute.
     * 
     * @var array
     */
    protected $extraAttributes = [];

    /**
     * Selects specific columns from the relationsip to set in the field's value.
     *
     * @param  array $columns Selected relationship columns
     * @return self
     */
    public function select($columns)
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Removes a specific field from the database interaction process.
     *
     * @return self
     */
    public function notRelatedToModel()
    {
        $this->notRelatedToModel = true;
        $this->isAttribute = false;
        $this->fillsBeforeSave = false;
        $this->fillsAfterSave = false;
        return $this;
    }

    /**
     * Has a value but it is not persisted in DB.
     *
     * @return self
     */
    public function doesNotFill()
    {
        $this->data([
            'doesNotFill' => true
        ]);
        return $this;
    }

    /**
     * When the field is an Eloquent relationship, this specifies which attribute to fill. The extraAttributes are optional and allow adding other constant columns/values to the relationship's model.
     *
     * @param  string $column   The column name
     * @param  array|null $extraAttributes Additional constant columns/values pairs (associative array).
     * @return self
     */
    public function feeds($column, $extraAttributes = [])
    {
        $this->feedsColumn = $column;
        $this->extraAttributes = $extraAttributes;
        return $this;
    }

    /**
     * Handles an input for a HasOne relationship of the model. 
     *
     * @param  string $relationship The HasOne relationship
     * @return self
     */
    public function forHasOne($relationship)
    {
        $this->forHasOne = $relationship;
        return $this;
    }

    /**
     * Gets the relation name if applicable.
     *
     * @return string|void
     */
    public function getRelationName()
    {
        if($this->fillsAfterSave && !$this->forHasOne)
            return $this->name;
    }

    /**
     * Returns the isAttribute boolean.
     *
     * @return bool
     */
    public function isAttribute()
    {
        return $this->isAttribute;
    }

    /**
     * Sets the field value from the Eloquent instance.
     *
     * @param Vuravel\Form\Builder|Model $record
     * @return void
     */
    protected function setValueFromDB($record)
    {
        if($this->notRelatedToModel)
            return;

        if($this->forHasOne) //var $record passed by reference needs to be persisted here...
            $record = $record->getHasOneRecord($this->forHasOne);

        method_exists($this, 'customSetValueFromDB') ?
            $this->customSetValueFromDB($record) :
            $this->normalSetValueFromDB($record);

        if(method_exists($this, 'prepareValueForFront'))
            $this->prepareValueForFront($record);

    }

    /**
     * The normal workflow for setting a value to the field.
     *
     * @param  Vuravel\Form\Model  $record  The record
     */
    protected function normalSetValueFromDB($record)
    {
        if($this->forHasOne){
            $this->isAttribute = $record->isAttribute($this->name);
            $this->fillsBeforeSave = false;//overriden, and we cannot set relation of HasOne
            $this->fillsAfterSave = true; //overriden, and we cannot set relation of HasOne
        }else{
            $this->isAttribute = $record->isAttribute($this->name);
            $this->fillsBeforeSave = $this->isAttribute || $record->isBelongsTo($this->name);
            $this->fillsAfterSave = !$this->isAttribute;
        }

        $this->setValue($record->getValueFromDB($this->name, $this->select) ?: $this->value);   
    }

    /**
     * Gets the value from the request and fills the attributes of the eloquent record.
     *
     * @param Illuminate\Http\Request $request
     * @param Vuravel\Form\Eloquent $record
     * @return void
     */
    public function fillBeforeSave($request, $record)
    {
        if(!$request->has($this->name) || $this->doesNotFillBeforeSave())
            return;

        $this->setAttributeFromRequest($request, $record);

        method_exists($this, 'customFillAttribute') ?
            $this->customFillAttribute($record) :
            $record->fillAttribute($this->name, $this->value);
        
        if($this->slug)
            $record->fillUniqueSlugAttribute($this->slug, $this->value);
    }

    /**
     * Sets the field value from the posted request.
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    protected function setAttributeFromRequest($request, $record)
    {
        $this->setValue($request->input($this->name));
    }

    /**
     * Gets the value from the request and parses it optionally (see methods overrides).
     *
     * @param Illuminate\Http\Request $request
     * @param Vuravel\Form\Eloquent $record
     * @return void
     */
    public function fillAfterSave($request, $record)
    {
        if(!$request->has($this->name) || $this->doesNotFillAfterSave())
            return;
        
        if($this->forHasOne){
            $hasOneRecord = $record->getHasOneRecord($this->forHasOne);
            $this->fillsBeforeSave = true;
            $this->fillBeforeSave($request, $hasOneRecord);
            $record->getRelation($this->forHasOne)->save($hasOneRecord->record);
            return;
        }

        $this->setRelationFromRequest($request, $record);

        if($this->feedsColumn)
            $this->setValue(array_merge([$this->feedsColumn => $this->value], $this->extraAttributes));

        $record->saveRelations($this->name, $this->value);
    }

    /**
     * Sets a relation field's value from the posted request.
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    protected function setRelationFromRequest($request, $record)
    {
        $this->setValue($request->input($this->name));
    }


    /**
     * Checks if the field does not fill or is readonly
     *
     * @return     Boolean  
     */
    protected function doesNotFillCondition()
    {
        return $this->data('doesNotFill') || $this->isReadOnly();
    }

    protected function doesNotFillBeforeSave()
    {
        return $this->doesNotFillCondition() || !$this->fillsBeforeSave;
    }

    protected function doesNotFillAfterSave()
    {
        return $this->doesNotFillCondition() || !$this->fillsAfterSave;
    }

}