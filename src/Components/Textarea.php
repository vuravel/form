<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Field;

class Textarea extends Field
{
    public $component = 'Textarea';

    public $data = [
        'rows' => 3
    ];

    /**
     * Sets the initial number of rows for the textarea. Default is 3.
     *
     * @param      integer  $rows   The number of rows
     *
     * @return     self    
     */
    public function rows($rows =  3)
    {
    	$this->data([
            'rows' => $rows
        ]);
    	return $this;
    }

}
