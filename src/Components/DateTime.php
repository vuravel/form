<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Date;

class DateTime extends Date
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        if(config('vuravel.default_datetime_format'))
    	   $this->dateFormat(config('vuravel.default_datetime_format'));

        $this->data([
            'dateFormat' => 'Y-m-d H:i',
            'altFormat' => 'Y-m-d H:i',
            'enableTime' => true
        ]);
    }

}
