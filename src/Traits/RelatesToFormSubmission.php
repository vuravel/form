<?php 
namespace Vuravel\Form\Traits;

trait RelatesToFormSubmission {
    
    /**
     * Submits the form. The default trigger is:
     * On click for Buttons/Links.
     * On change for fields (after blur).
     *
     * @return self
     */
    public function submitsForm()
    {
        return $this->updateDefaultTrigger(function($e) {
            $e->submitsForm();
        });
    }

    /**
     * Submits the form when a user types in a field. By default, the request is debounced by 500ms.
     * 
     * @return self
     */
    public function submitsOnInput()
    {
        return $this->onInput(function($e){
            
            $e->submitsForm()->debounce();
            
        });
    }

    /**
     * Submits the form when the ENTER key is released.
     *
     * @return self
     */
    public function submitsOnEnter()
    {
        return $this->onEnter(function($e){
            $e->submitsForm();
        });
    }

    /**
     * Cancel default behavior of certain components submitting on Enter key release.
     *
     * @return self
     */
    public function dontSubmitOnEnter()
    {
        return $this->data(['noSubmitOnEnter' => true]);
    }

    /**
     * Hides the submission indicators (spinner, success, error).
     *
     * @return self
     */
    public function hideIndicators()
    {
        return $this->data(['hideIndicators' => true]);
    }

    /**
     * Hides the field or element but it's still there.
     *
     * @return self
     */
    public function displayNone()
    {
        return $this->data(['displayNone' => true]);
    }

}