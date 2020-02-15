<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Traits\HasInputAttributes;
use Vuravel\Form\Field;

class Input extends Field
{
    use HasInputAttributes;
    
    public $component = 'Input';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('text');

        $this->submitsOnEnter();
    }

}
