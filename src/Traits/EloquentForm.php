<?php 
namespace Vuravel\Form\Traits;

use Vuravel\Form\Builder;
use Vuravel\Form\Model;

trait EloquentForm {
    
    /**
     * The model's namespace that the form links to.
     *
     * @var string
     */
    public static $model;

    /**
     * The table's identifier of format "{$connection}.{$table}" that the form links to.
     *
     * @var string
     */
    public $table;
    
    /**
     * The record's key (model or table row id, for ex.) used when reading, updating or deleting it.
     *
     * @var string
     */
    public $recordKey;
    
    /**
     * The record's key name (most commonly 'id'). Used in Builder only, not Eloquent Model.
     *
     * @var string
     */
    public $recordKeyName = 'id';

    /**
     * The main record instance targeted by the form (Eloquent model or Table record).
     *
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public $record;
    
    /**
     * If you wish to reload the form after submit/saving the model, set to true.
     *
     * @var boolean
     */
    public static $refresh = false;
    
    /**
     * Construct a Form object, and prepare a retrieved Model and the form's components.
     *
     * @param  mixed  $recordKey
     * @return Vuravel\Form\Form
     */
    public static function findStatic($recordKey)
    {
        $form = new static(true);
        $form->recordKey = $recordKey;
        return $form->bootToSession();
    }

    /**
     * Construct a Form object, and prepare a retrieved Model and the form's components.
     *
     * @param  mixed  $recordKey
     * @return Vuravel\Form\Form
     */
    public function findNonStatic($recordKey)
    {
        $this->recordKey = $recordKey;
        return $this->bootToSession();
    }

    /**
     * Initialize the record if form has a static property $model or $table defined.
     *
     * @return Vuravel\Form\Form
     */
    protected function prepareRecord()
    {
        if( $this->table )
            $this->record = Builder::create($this->table, $this->recordKey, $this->recordKeyName);

        if( static::$model )
        	$this->record = Model::create(static::$model, $this->recordKey);

        return $this;
    }

    /**
     * Initialize the record if form has a static property $model or $table defined.
     *
     * @return Vuravel\Form\Http\Requests\FormValidationRequest
     */
    public function updateRecordFromRequest($request)
    {
        $model = $this->newModelInstanceFromRequest($request);
        $this->beforeSaveHook($model);

        if(defined(get_class($model).'::CREATED_BY') && !$model->getKey() && $model::CREATED_BY)
            $model->{$model::CREATED_BY} = auth()->user()->id;
        if(defined(get_class($model).'::UPDATED_BY') && $model::UPDATED_BY)
            $model->{$model::UPDATED_BY} = auth()->user()->id;

        $model->save();
        $this->recordKey = $model->getKey();

        return $this->saveRelationsFromRequest($request);
    }

    /**
     * Gets an unsaved instance of the record
     *
     * @return Vuravel\Form\Http\Requests\FormValidationRequest
     */
    public function newModelInstanceFromRequest($request)
    {
        $this->blueprint->fillModelFromRequest($request, $this->record);
        return $this->record->record;
    }

    /**
     * Handles relationship saving of the record from request data
     *
     * @return Vuravel\Form\Http\Requests\FormValidationRequest
     */
    public function saveRelationsFromRequest($request)
    {
        $this->afterSaveHook($this->record->record);
        $this->blueprint->assignRelationsFromRequest($request, $this->record);
        $this->completedHook($this->record->record);
        return $this->returnResponse($this->record->record) ?: $this->record->record;
    }

    /**
     * Returns the record being updated/inserted by the form or null if the form is not linked to a Record.
     *
     * @return Illuminate\Database\Eloquent\Model|Builder|null
     */
    public function record()
    {
        return $this->record ? $this->record->record : null;
    }

    public function attribute($attribute)
    {
        return $this->record ? $this->record->record->{$attribute} : null;
    }


    public function creating()
    {
        return !$this->recordKey;
    }


}