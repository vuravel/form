<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Flex;

class FlexAround extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyAround();
    }
}
