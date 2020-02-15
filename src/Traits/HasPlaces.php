<?php 
namespace Vuravel\Form\Traits;

trait HasPlaces {

	public function places()
	{
		return $this->morphMany('Vuravel\Form\Models\Place', 'model');
	}

	public function place()
	{
		return $this->morphOne('Vuravel\Form\Models\Place', 'model');
	}

}