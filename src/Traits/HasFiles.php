<?php 
namespace Vuravel\Form\Traits;

trait HasFiles {

	public function files()
	{
		if(config('vuravel.files.isMonogamous')){
			return $this->morphMany('Vuravel\Form\Models\File', 'attachable');
		}else{
			//for file library (polyamorous)
			return $this->morphToMany('Vuravel\Form\Models\File', 'attachable');			
		}
	}

	public function file()
	{
		if(config('vuravel.files.isMonogamous')){
			return $this->morphOne('Vuravel\Form\Models\File', 'attachable');
		}else{
			//morphToOne doesn't seem to exist
			return $this->morphToMany('Vuravel\Form\Models\File', 'attachable')->limit(1);			
		}
	}

}