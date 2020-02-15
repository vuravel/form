<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Multiformbtm extends Model
{	
	public function objs() 
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Obj'); 
	}

}
