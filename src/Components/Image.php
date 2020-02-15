<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\File;
use Vuravel\Form\Components\Traits\UploadsImages;

class Image extends File
{    
    use UploadsImages;
    
    public $component = 'Image';
    
    public function prepareValueForFront($record)
    {
        $this->value = $this->value ? $this->transformFromDB($this->value) : null;
    }

}
