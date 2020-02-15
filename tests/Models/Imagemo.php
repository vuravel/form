<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Imagemo extends Model
{	
	public function obj() 
	{
		return $this->morphTo(); 
	}

}
