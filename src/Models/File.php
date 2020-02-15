<?php
namespace Vuravel\Form\Models;
use Vuravel\Core\Eloquent\Model;

class File extends Model
{
	protected $connection = 'mysql';
	
	public $fillable = [
		config('vuravel.files_attributes.name'),
		config('vuravel.files_attributes.path'),
		config('vuravel.files_attributes.mime_type'),
		config('vuravel.files_attributes.size')
	];

	public function delete()
	{
    	if($this && file_exists($path = storage_path('app/public'.substr($this->{config('vuravel.files_attributes.path')},7))))
			unlink($path);
		parent::delete();
	}
	
}
