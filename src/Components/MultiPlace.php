<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Place;

class MultiPlace extends Place
{
    public $multiple = true;

    protected function setAttributeFromRequest($request, $record)
    {
		$this->setValue(collect($request->input($this->name))->map(function($place){
            return $this->placeToDB($place);
        }));
    }

    protected function setRelationFromRequest($request, $record)
    {
    	if($this->value && $this->value->count()){
            $keepIds = collect($request->input($this->name))
                        ->map(function($place){ return json_decode($place)->external_id ?? null; })->all();
            
            $this->value->filter(function($place) use($keepIds) { 
                    return !in_array($place->external_id ?? '',$keepIds); 
                })->each(function($place) {
                    $place->delete(); //No detach, onDelete('cascade') should give the choice.
                });
        }
        $this->setValue(null);
        if($places = $request->input($this->name))
        	$this->setValue(collect($places)->map(function($place){
                if(! (json_decode($place)->id ?? false) ){
                    return $this->placeToDB($place);
                }
	        })->filter());
    }

}
