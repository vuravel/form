<?php

namespace Vuravel\Form\Exceptions;

use RuntimeException;

class RelationNotFoundException extends RuntimeException
{
	public $name;

	public function setMessage($name)
    {
        $this->name = $name;
        $this->message = "No attribute or relation found for {$name}";
        return $this;
    }
}
