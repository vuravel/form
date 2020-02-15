<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Input;
use Illuminate\Support\Facades\Hash;

class Password extends Input
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('password');
    }

    protected function setAttributeFromRequest($request, $record)
    {
        $this->setValue(Hash::make($request->input($this->name)));
    }

}
