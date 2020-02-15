<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Layout;

class Rows extends Layout
{
    public $component = 'Rows';
    public $menuComponent = 'Rows';

    public $data = [
    	'childMargins' => true
    ];

    public function noMargins()
    {
    	return $this->data(['childMargins' => false]);
    }

}
