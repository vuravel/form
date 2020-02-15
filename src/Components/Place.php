<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Field;
use Illuminate\Support\Arr;

class Place extends Field
{
    public $component = 'Place';

    protected function setAttributeFromRequest($request, $record)
    {
        $this->setValue(null);

        if($place = $request->input($this->name))
        	$this->setValue($this->placeToDB($place));
    }

    protected function setRelationFromRequest($request, $record)
    {
    	$oldPlace = $this->value;
        $this->setValue(null);

        if($place = $request->input($this->name)){
        	$place = $this->placeToDB($place[0]);
        	if(($oldPlace->external_id ?? null ) != $place['external_id'] )
        		$this->setValue($place);
        }
    }

    protected function placeToDB($place)
    {
    	$place = json_decode($place, true);
    	if($address_components = Arr::get($place, 'address_components')){
	    	$result = [];
	    	foreach ($address_components as $value) {
	    		if(in_array('street_number', $value['types']))
	    			$result['street_number'] = $value['long_name'];
	    		if(in_array('route', $value['types']))
	    			$result['street'] = $value['long_name'];
	    		if(in_array('locality', $value['types']))
	    			$result['city'] = $value['long_name'];
	    		if(in_array('administrative_area_level_1', $value['types']))
	    			$result['state'] = $value['long_name'];
	    		if(in_array('country', $value['types']))
	    			$result['country'] = $value['long_name'];
	    		if(in_array('postal_code', $value['types']))
	    			$result['postal_code'] = $value['long_name'];
	    	}
	        return array_merge($result, [
	            'address' => $place['formatted_address'],
	            'lat' => $place['geometry']['location']['lat'],
	            'lng' => $place['geometry']['location']['lng'],
	            'external_id' => $place['id']
	        ]);
	    }else{
	    	return $place;
	    }
    }
}
