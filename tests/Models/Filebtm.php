<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Filebtm extends Model
{
	public $fillable = ['name', 'path', 'mime_type', 'size'];
	
	public function objs() 
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Obj'); 
	}

}
