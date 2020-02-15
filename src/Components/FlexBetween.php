<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Flex;

class FlexBetween extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyBetween();
    }
}
