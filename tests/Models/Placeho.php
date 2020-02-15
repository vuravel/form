<?php
namespace Vuravel\Form\Tests\Models;
use Illuminate\Database\Eloquent\Model;

class Placeho extends Model
{
	public $fillable = ['address', 'lat', 'lng', 'external_id', 'street_number', 'street', 'city', 'state', 'country', 'postal_code'];
	
	public function obj() 
	{
		return $this->belongsTo('Vuravel\Form\Tests\Models\Obj'); 
	}

}
