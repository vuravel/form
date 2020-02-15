<?php 

use Illuminate\Support\Str;

if (! function_exists('form')) {
	function form($form, $dontBoot = false)
	{
		$form = ucfirst(Str::camel($form));
		if($formClass = formExists($form)){
			return new $formClass($dontBoot);
		}else{
			abort(404, 'Class App\\Forms\\'.$form.' not found');
		}
	}
}

if (! function_exists('formExists')) {
	function formExists($form)
	{
		if(class_exists('App\\Forms\\'.$form)){
			return 'App\\Forms\\'.$form;
		}elseif(class_exists('Vuravel\\Form\\Forms\\'.$form)){
			return 'Vuravel\\Form\\Forms\\'.$form; //useless, no forms exist...
		}else{
			return false;
		}
	}
}

if (! function_exists('response422')) {
	function response422($errors)
	{
		//errors values should be arrays - because can have multiple ones...
		foreach ($errors as $key => $value) {
			$errors[$key] = is_array($value) ? $value : [$value];
		}
		return response()->json(['errors' => $errors], 422);
	}
}

if (! function_exists('responseInModal')) {
	function responseInModal($view = null)
	{
		return response()->json([
			'message' => $view->render(),
			'inModal' => true
		]);
	}
}

if (! function_exists('responseInSuccessModal')) {
	function responseInSuccessModal($message)
	{
		return responseInModal( view('vuravel-form::success', ['message' => $message]) );
	}
}

if (! function_exists('responseInErrorModal')) {
	function responseInErrorModal($message)
	{
		return responseInModal( view('vuravel-form::error', ['message' => $message]) );
	}
}

if (! function_exists('thumb')) {
	function thumb($path)
	{
		return substr($path, 0, strrpos( $path, '.')).
			   '_thumb.'.
			   substr($path, strrpos($path,'.') + 1);
	}
}

if (! function_exists('assetThumb')) {
	function assetThumb($path)
	{
		return thumb(asset($path));
	}
}