<?php
namespace Vuravel\Form\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Vuravel\Form\Tests\Models\Filemtm;
use Vuravel\Form\Tests\Models\Imagemtm;
use Vuravel\Form\Tests\Models\Multiformmtm;
use Vuravel\Form\Tests\Models\Placemtm;
use Vuravel\Form\Tests\Models\Tagmtm;

class Obj extends Model
{
	protected $casts = [
        'tags' => 'array',
        'file' => 'array',
        'image' => 'array',
        'place' => 'array',
        'multiform' => 'array'
    ];

    /********** BelongsToMany **********/
	public function tagbtms()
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Tagbtm')->withTimestamps();
	}

	public function filebtms()
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Filebtm')->withTimestamps();
	}

	public function imagebtms()
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Imagebtm')->withTimestamps();
	}

	public function placebtms()
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Placebtm')->withTimestamps();
	}

	public function multiformbtms()
	{
		return $this->belongsToMany('Vuravel\Form\Tests\Models\Multiformbtm')->withTimestamps();
	}


	/********** HasMany **********/
	public function filehms()
	{
		return $this->hasMany('Vuravel\Form\Tests\Models\Filehm');
	}

	public function imagehms()
	{
		return $this->hasMany('Vuravel\Form\Tests\Models\Imagehm');
	}

	public function placehms()
	{
		return $this->hasMany('Vuravel\Form\Tests\Models\Placehm');
	}

	public function multiformhms()
	{
		return $this->hasMany('Vuravel\Form\Tests\Models\Multiformhm');
	}


	/********** HasOne **********/
	public function fileho()
	{
		return $this->hasOne('Vuravel\Form\Tests\Models\Fileho');
	}

	public function imageho()
	{
		return $this->hasOne('Vuravel\Form\Tests\Models\Imageho');
	}

	public function placeho()
	{
		return $this->hasOne('Vuravel\Form\Tests\Models\Placeho');
	}

	public function multiformho()
	{
		return $this->hasOne('Vuravel\Form\Tests\Models\Multiformho');
	}

	/********** MorphOne **********/
	public function filemo()
	{
		return $this->morphOne('Vuravel\Form\Tests\Models\Filemo', 'model');
	}

	public function imagemo()
	{
		return $this->morphOne('Vuravel\Form\Tests\Models\Imagemo', 'model');
	}

	public function placemo()
	{
		return $this->morphOne('Vuravel\Form\Tests\Models\Placemo', 'model');
	}

	public function multiformmo()
	{
		return $this->morphOne('Vuravel\Form\Tests\Models\Multiformmo', 'model');
	}


	/********** MorphMany **********/
	public function filemms()
	{
		return $this->morphMany('Vuravel\Form\Tests\Models\Filemm', 'model');
	}

	public function imagemms()
	{
		return $this->morphMany('Vuravel\Form\Tests\Models\Imagemm', 'model');
	}

	public function placemms()
	{
		return $this->morphMany('Vuravel\Form\Tests\Models\Placemm', 'model');
	}

	public function multiformmms()
	{
		return $this->morphMany('Vuravel\Form\Tests\Models\Multiformmm', 'model');
	}

    /********** MorphToMany **********/
	public function tagmtms()
	{
		return $this->morphToMany(Tagmtm::class, 'model', 'obj_tagmtm')->withTimestamps();
	}

	public function filemtms()
	{
		return $this->morphToMany(Filemtm::class, 'model', 'obj_filemtm')->withTimestamps();
	}

	public function imagemtms()
	{
		return $this->morphToMany(Imagemtm::class, 'model', 'obj_imagemtm')->withTimestamps();
	}

	public function placemtms()
	{
		return $this->morphToMany(Placemtm::class, 'model', 'obj_placemtm')->withTimestamps();
	}

	public function multiformmtms()
	{
		return $this->morphToMany(Multiformmtm::class, 'model', 'obj_multiformmtm')->withTimestamps();
	}


}
