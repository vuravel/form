<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Field;

class CKEditor extends Field
{
    use Traits\CKEditorTrait;
    
    public $component = 'CKEditor';

    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->setDefaultToolbar();
    }
}
