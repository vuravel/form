<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Multiformhm extends Model
{
	public function obj() 
	{
		return $this->belongsTo('Vuravel\Form\Tests\Models\Obj'); 
	}

}
