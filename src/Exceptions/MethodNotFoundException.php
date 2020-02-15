<?php

namespace Vuravel\Form\Exceptions;

use RuntimeException;

class MethodNotFoundException extends RuntimeException
{
	public $name;

	public function setMessage($name)
    {
        $this->name = $name;
        $this->message = "No method {$name} found on form while trying to retrieve options";
        return $this;
    }
}
