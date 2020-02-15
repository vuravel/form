<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Flex;

class FlexCenter extends Flex
{
    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->justifyCenter();
    }
}
