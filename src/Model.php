<?php

namespace Vuravel\Form;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Vuravel\Form\Exceptions\RelationNotFoundException;
use Vuravel\Form\Record;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne, HasMany, MorphOne, MorphMany, MorphTo, MorphToMany};

class Model extends Record
{
    /**
     * Constructs a Vuravel\Form\Eloquent object
     *
     * @param  string $model
     * @param  string $recordId
     * @return void
     */
    public function __construct($model, $recordId)
    {
        $this->recordId = $recordId;
		$this->record = new $model();
        
        $this->connection = $this->record->getConnectionName() ?: $this->record->getConnection()->getName();
        $this->table = $this->record->getTable();
        $this->setTableColumns();

        if($this->recordId)
            $this->record = $this->record->findOrFail($this->recordId);
    }

    /**
     * Gets the value according to the name and optional select columns.
     *
     * @param  string $name
     * @param  array $select
     * @return mixed
     */
    public function getValueFromDB($name, $select = null)
    {
        return !$this->hasValue($name) ? null :
                ($this->isAttribute($name) ? $this->record->{$name} :
                ($select ? $this->getRelation($name)->select($select)->get() : $this->record->{$name} ));
    }

    /**
     * Checks if the record has a value.
     *
     * @param  string $name
     * @return mixed
     */
    public function hasValue($name)
    {
        if($this->isAttribute($name)){
            return $this->record->{$name};
        }else{
            if($relation = $this->getRelation($name)){
                return $relation->count();
            }else{
                throw (new RelationNotFoundException)->setMessage($name);
            }
        }
    }
    
    /**
     * Gets all the models that could be related.
     *
     * @param  string $relation
     * @param  array $select
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRelatedModels($relation, $order, $select = null)
    {
        if($this->isMorphToMany($relation) || $this->isBelongsToMany($relation)){
            return $select ? 
                $this->getRelation($relation)->select($select)->getRelated()->get() : 
                $this->getRelation($relation)->getRelated()->get();
        }

        $relation = $this->getRelation($relation);
        
        if($select)
            $relation = $relation->select($select);

        $relationQuery = $relation->getBaseQuery();

        //to remove specific model where, we want all possible relations, or empty model where
        $primaryWhere = array_shift($relationQuery->wheres); 

        if(Arr::get($primaryWhere,'value'))
            array_shift($relationQuery->bindings['where']); //to remove the where value binding

        foreach ($order as $column => $direction) {
            $relationQuery = $relationQuery->orderBy($column, $direction);
        }
        
        return $relationQuery->get();
    }

    /**
     * Fill the model's attribute value according to the field's name.
     *
     * @param  string $name
     * @return boolean
     */
    public function fillAttribute($name, $value)
    {
        if($this->isBelongsTo($name)){
            $this->record->{$this->getRelation($name)->getForeignKeyName()} = $value;
        }else{
            $this->record->{$name} = $value;
        }
    }

    /**
     * Fill the model's attribute value according to the field's name.
     *
     * @param  string $name
     * @return boolean
     */
    public function fillUniqueSlugAttribute($name, $value)
    {
        $initialSlug = Str::slug($value);
        if($this->record->{$name} == $initialSlug) //if it already has the slug and it is still the same
            return;

        $uniqueSlug = $initialSlug;
        $i = 1;
        while($this->getQueryBuilder()->where($name, $uniqueSlug)->count())
        {
            $uniqueSlug = $initialSlug.'-'.$i;
            $i++;
        }
        $this->fillAttribute($name, $uniqueSlug);
    }

    /**
     * Fill the model's attribute value according to the field's name.
     *
     * @param  string $name
     * @return boolean
     */
    public function saveRelations($name, $value)
    {
        if($this->isHasMany($name)){

            $this->saveHasMany($name, $value);

        }elseif($this->isHasOne($name)){

            $this->saveHasOne($name, $value);

        }elseif($this->isMorphOne($name)){

            if(!$value) return;
            $this->getRelation($name)->create($value);

        }elseif($this->isMorphMany($name)){

            if(!$value) return;
            $this->getRelation($name)->createMany($value->all());
        }elseif ($this->isBelongsToMany($name)) {
            //To review if Pivot has Author...
            /*$relationIds = Arr::pluck($value, 'value');
            $relationIds = array_combine($relationIds, array_fill(0, count($relationIds), ['user_id' => \Auth::user()->id]));*/
            $this->getRelation($name)->sync($value);
        }elseif ($this->isBelongsTo($name)) {
            //just delete the old one
            
        }elseif($this->isMorphToMany($name)){
            $this->getRelation($name)->sync($value);
        }
    }

    /**
     * Check if the method exists for the Eloquent model.
     *
     * @param  string $relation
     * @return Boolean
     */
    public function methodExists($relation)
    {
        return method_exists($this->record, $relation);
    }

    /**
     * Get the model's eloquent relation.
     *
     * @param  string $column
     * @return Eloquent\Relationship|null
     */
    public function getRelation($column)
    {
        return method_exists($this->record, $column) ?
                $this->record->{$column}() : 
                null;
    }

    /**
     * Get the model's relation ship type.
     *
     * @param  string $column
     * @return string
     */
    public function getRelationType($column)
    {
        return $this->getRelation($column) ? class_basename($this->getRelation($column)) : null;
    }

    /**
     * Is the model's relation ship ***
     *
     * @param  string $column
     * @return string
     */
    public function isBelongsTo($column){ return $this->getRelation($column) instanceof BelongsTo; }
    public function isHasOne($column){ return $this->getRelation($column) instanceof HasOne; }
    public function isHasMany($column){ return $this->getRelation($column) instanceof HasMany; }
    public function isBelongsToMany($column){ return $this->getRelation($column) instanceof BelongsToMany; }
    public function isMorphOne($column){ return $this->getRelation($column) instanceof MorphOne; }
    public function isMorphMany($column){ return $this->getRelation($column) instanceof MorphMany; }
    public function isMorphTo($column){ return $this->getRelation($column) instanceof MorphTo; }
    public function isMorphToMany($column){ return $this->getRelation($column) instanceof MorphToMany; }


    public function getStoragePath($column)
    {
        if($this->isAttribute($column))
            return parent::getStoragePath($column);

        $relation = $this->getRelation($column)->getRelated();
        return $relation->getConnectionName().'/'.$relation->getTable().'/path';
    }


    /* new saving eloquent relationships (To remove the need to enter fillables) */
    public function saveHasOne($column, $attributes)
    {
        if(!$attributes) return;

        $related = $this->getRelation($column)->getRelated();
        foreach ($attributes as $key => $attribute) {
            $related->{$key} = $attribute;
        }
        $this->getRelation($column)->save($related);
    }

    public function saveHasMany($column, $arrayOfAttributes)
    {
        if(!$arrayOfAttributes) return;
        
        foreach ($arrayOfAttributes as $attributes) {
            $this->saveHasOne($column, $attributes);
        }
    }


    public function getKey()
    {
        return $this->record->getKey();
    }

    public function getKeyName()
    {
        return $this->record->getKeyName();
    }

    public function refresh($relationsArray)
    {
        $this->record = $this->record->fresh()->load($relationsArray); //Changed to fresh for 2 reasons:
        //1. It's redundant to do refresh()->load()
        //2. refresh() cannot handle possible null relations in Model
        //Downside: lost the response 201 when creating an object
    }

    public function getHasOneRecord($relationName)
    {
        $hasOneClass = get_class($this->getRelation($relationName)->getRelated());
        $hasOneModel = $this->getValueFromDB($relationName);
        $hasOneKey = $hasOneModel ? $hasOneModel->getKey() : null; 
        return self::create($hasOneClass, $hasOneKey);
    }

}