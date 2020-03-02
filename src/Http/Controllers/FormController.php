<?php

namespace Vuravel\Form\Http\Controllers;

use App\Http\Controllers\Controller;
use Vuravel\Core\Http\Requests\SessionAuthorizationRequest;
use Vuravel\Form\Components\Select;
use Vuravel\Form\Exceptions\MethodNotFoundException;
use Vuravel\Form\Exceptions\HandleMethodNotFoundException;
use Vuravel\Form\Http\Requests\FormValidationRequest;

class FormController extends Controller
{
    /**
     * Calls user-defined handle() method in Form Class.
     *
     * @param  Vuravel\Form\Http\Requests\FormValidationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function handleSubmit(FormValidationRequest $request)
    {
        $form = $request->vlObject();

        if($method = $request->header('X-Vuravel-Handle')){
            if(method_exists($form, $method))
                return $form->{$method}($request);

            throw (new HandleMethodNotFoundException)->setMessage($method);
        }

        return $form->handle($request);
    }

    /**
     * Includes additional fields from the Form Class.
     *
     * @param  Vuravel\Core\Http\Requests\SessionAuthorizationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function includeFields(SessionAuthorizationRequest $request)
    {
        return $request->vlObject();
    }

    /**
     * Updates the database according to the form specifications.
     *
     * @param  Vuravel\Form\Http\Requests\FormValidationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateRecord(FormValidationRequest $request)
    {
        $form = $request->vlObject();

        if($form::$refresh){
            $form->updateRecordFromRequest($request);
            $form = $form->bootToSession(); //refreshing the form
            $form->overwriteUri($request->vlUri(), $request->vlMethods());
            return response()->json(['form' => $form], 202);
        }else{
            return $form->updateRecordFromRequest($request); //update the record
        }
    }

    /**
     * Gets select options by ajax with optional search feature
     * @param  Vuravel\Core\Http\Requests\SessionAuthorizationRequest $request
     * @return array $results
     */
    public function getSearchedSelectOptions(SessionAuthorizationRequest $request)
    {
        $form = $request->vlObject();
        $method = $request->input('method');

        if(method_exists($form, $method)){
            return Select::transformOptions($form->{$method}(request('search')));
        }else{
            throw (new MethodNotFoundException)->setMessage($method);
        }
    }

    /**
     * Gets the updated select options after a related object has been saved
     * @param  Vuravel\Core\Http\Requests\SessionAuthorizationRequest $request
     * @return array $results
     */
    public function getUpdatedSelectOptions(SessionAuthorizationRequest $request)
    {
        foreach ($request->vlObject()->getFieldComponents() as $field) {
            if($field->name == $request->input('selectName'))
                return $field->options;
        }
    }
}
