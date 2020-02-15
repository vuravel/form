<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Fileho extends Model
{
	public $fillable = ['name', 'path', 'mime_type', 'size'];

	public function obj() 
	{
		return $this->belongsTo('Vuravel\Form\Tests\Models\Obj'); 
	}

}
