<?php
namespace Vuravel\Form;

use Vuravel\Elements\Element;

class Component extends Element
{
    use Traits\UsableInForms;
    use Traits\UsableInCatalogs;

    public function prepareComponent($form)
    {
        if(method_exists($form, 'authorize') && !$form->authorize())
            if($this->data('submitsForm'))
            	$this->displayNone();
    }
    
}