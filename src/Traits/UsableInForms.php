<?php 
namespace Vuravel\Form\Traits;

use Vuravel\Form\{Field, Layout, Trigger};

trait UsableInForms {

    /**
     * Passes Form attributes to the component.
     *
     * @param  Vuravel\Form\Form $form
     * @return void
     */
    public function prepareComponent($form)
    {
        //do nothing
    }    

    public function getFieldComponents($form)
    {
        return array_merge(
            $this->isField() ? [$this] : [],
            $this->data('includes') ? $form->{$this->data('includes')}() : []
        );

        //to delete
        if($this->isField())
            return [$this];
    }

    public function isField()
    {
        return $this instanceof Field;
    }

    public function isLayout()
    {
        return $this instanceof Layout;
    }

    public function isTrigger()
    {
        return $this instanceof Trigger;
    }

}