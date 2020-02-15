<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;
use Vuravel\Form\Tests\Models\Obj;

class Tagbtm extends Model
{	
	public function objs() 
	{
		return $this->belongsToMany(Obj::class); 
	}

}
