<?php

namespace Vuravel\Form;

use Vuravel\Form\Components\Rows;
use Vuravel\Core\Contracts\Routable;
use Vuravel\Core\Traits\{IsRoutable, HasMetaTags};
use Vuravel\Form\Traits\{HasValidationRules, PersistsInSession, EloquentForm};

class Form extends Rows implements Routable
{
    use IsRoutable, HasMetaTags, HasValidationRules, PersistsInSession, EloquentForm;

    public $component = 'Rows';
    public $menuComponent = 'Form';
    public $partial = 'VlForm';

    protected $preventSubmit = false; //prevent submitting a form (emits only)
    protected $emitFormData = true;

    protected $submitTo = null; //if the route is simple (no parameters)
    protected $submitMethod = 'POST';

    protected $redirectTo = null;
    protected $redirectMessage = 'Success! Redirecting...';

    const DB_UPDATE_ROUTE = 'vuravel-form.db.update';
    const HANDLE_SUBMIT_ROUTE = 'vuravel-form.handle';

    protected $blueprint;

    /**
     * Get the Components displayed in the form.
     *
     * @return array
     */
    public function components()
    {
        return [];
    }

    /**
     * Get the request's validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get the Form's post url to be overridden.
     *
     * @return string
     */
    public function submitUrl()
    {
        return '';
    }

    /**
     * Construct a Form object, an optional Eloquent Model and the form's components.
     *
     * @return Vuravel\Form\Form
     */
    public function __construct($dontBoot = false)
    {
        if(!$dontBoot)
            $this->bootToSession();
    }

    /**
     * Boot a Form object, an optional Eloquent Model and the form's components.
     *
     * @return Vuravel\Form\Form
     */
    public function vlBoot()
    {
        return $this->createdHook()
                    ->prepareForm()
                    ->prepareRecord()
                    ->prepareComponents()
                    ->addValidationRules($this->rules())
                    ->bootedHook();
    }

    public function startReboot()
    {
        return $this->createdHook()
                    ->prepareForm()
                    ->prepareRecord();
    }

    public function finishReboot()
    {
        return $this->prepareComponents()
                    ->addValidationRules($this->rules())
                    ->bootedHook();
    }

    /**
     * Initialize the form attributes.
     *
     * @return void
     */
    protected function prepareForm()
    {
        $this->setBootableId();

        $this->class($this->class ?: class_basename($this));

        $this->style($this->style);

        $this->submitBehavior();

        $this->redirectBehavior();

        return $this;
    }

    /**
     * Initialize the submit behavior.
     *
     * @return void
     */
    protected function submitBehavior()
    {
        $this->data([
            'emitFormData' => $this->emitFormData
        ]);

        if($this->preventSubmit)
            return;

        $this->data([
            'submitUrl' => $this->submitTo ? $this->guessRoute($this->submitTo) :
                ($this->submitUrl() ? : 
                    ((static::$model || $this->table) ? route(self::DB_UPDATE_ROUTE) : 
                        (method_exists($this, 'handle') ? route(self::HANDLE_SUBMIT_ROUTE) : null ))),
            'submitMethod' => $this->submitMethod
        ]);
    }

    /**
     * Initialize the redirect behavior.
     *
     * @return void
     */
    protected function redirectBehavior()
    {
        $this->data([
            'redirectUrl' => $this->redirectTo ? $this->guessRoute($this->redirectTo) : null,
            'redirectMessage' => __($this->redirectMessage)
        ]);
    }

    /**
     * Prepare the components' attributes and values.
     *
     * @param  array  $components
     * @return void
     */
    public function prepareComponents()
    {
        $this->blueprint = new Blueprint($this->components());
        $this->components = $this->blueprint->getPreparedComponents($this);

        return $this;
    }

    /**
     * Gets the field components of the form.
     *
     * @return array
     */
    public function getFieldComponents()
    {
        return $this->blueprint->getFieldComponents();
    }

    /**
     * Sets a specific return response for the form.
     *
     * @param  mixed  $model
     * @return Response
     */
    public function returnResponse($model)
    {
        if(method_exists($this, 'response'))
            return $this->response($model);

        return null;
    }

    /**
     * Shortcut method to render a form into it's Vue component.
     *
     * @return     string
     */
    public static function vueRender($form)
    {
        return '<vl-form :vcomponent="'.htmlspecialchars($form).'"></vl-form>';
    }

    public static function duplicateStaticMethods()
    {
        return ['find', 'store', 'render'];
    }
}