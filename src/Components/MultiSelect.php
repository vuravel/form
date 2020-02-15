<?php

namespace Vuravel\Form\Components;

class MultiSelect extends Select
{
    public $multiple = true;

    public $value = [];


    protected function pluckValueForFront()
    {
    	if($key = $this->getValueKeyName()) 
        	$this->setValue($this->value->pluck($key));
    }

    protected function valueAsCollection()
    {
    	return collect($this->value);
    }

    protected function getFirstValue()
    {
        return $this->value[0]; //!!overriden in Select
    }
}
