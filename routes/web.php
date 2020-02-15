<?php

Route::namespace('Vuravel\Form\Http\Controllers')
	->middleware('web')->as('vuravel-form.')
	->group(function(){

	Route::post('vuravel/form/handle', 'FormController@handleSubmit')
		->name('handle');

	Route::post('vuravel/form/db/update', 'FormController@updateRecord')
		->name('db.update');

	Route::post('vuravel/form/select-ajax-options', 'FormController@getSearchedSelectOptions')
		->name('select-ajax-options');

	Route::post('vuravel/form/select-updated-options', 'FormController@getUpdatedSelectOptions')
		->name('select-updated-options');

});