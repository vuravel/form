<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Translatable;

class TranslatableEditor extends Translatable
{
    use Traits\CKEditorTrait;
    
    public $component = 'TranslatableEditor';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->setDefaultToolbar();
    }
}
