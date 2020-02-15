<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Flex;

class FlexEnd extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyEnd();
    }
}
