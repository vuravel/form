<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\MultiFile;
use Vuravel\Form\Components\Traits\UploadsImages;

class MultiImage extends MultiFile
{
    use UploadsImages;
    
    public $component = 'Image';

    public function prepareValueForFront($record)
    {
        $this->value = collect($this->value)->map(function($image){
				        		return $this->transformFromDB($image);
				        	});
    }

}
