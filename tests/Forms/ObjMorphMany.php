<?php
namespace Vuravel\Form\Tests\Forms;
use Vuravel\Form\Components\{Input, MultiFile, MultiImage, Html, MultiPlace, MultiForm, Button};
use Vuravel\Form\Form;
use Vuravel\Form\Tests\Forms\MultiForm as ChildForm;
use Vuravel\Form\Tests\Models\Obj;

class ObjMorphMany extends Form
{
	public static $model = Obj::class;

	public function components()
	{
		return [
			Input::form('Title'),
			Html::form('No Select with HasMany (impossible)'),
			MultiFile::form('Filemms'),
			MultiImage::form('Imagemms'),
			MultiPlace::form('Placemms'),
			//MultiForm::form(ChildForm::class),
			Button::form('Save')->submitsForm()
		];
	}

	public function rules()
	{
		return [];
	}
	
	public function authorize()
	{
		return true;
	}
}