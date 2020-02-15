<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Placebtm extends Model
{	
	public function objs() 
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Obj'); 
	}

}
