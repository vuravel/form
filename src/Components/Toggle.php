<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Field;

class Toggle extends Field
{
    public $component = 'Toggle';

    protected function setValue($value)
    {
        $this->value = $value ? 1 : 0;
    }
}
