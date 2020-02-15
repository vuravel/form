<?php 
namespace Vuravel\Form\Traits;
use Vuravel\Core\Traits\PersistsInSession as Sessionable;

trait PersistsInSession {

    use Sessionable;

    /**
     * Saves the necessary parameters to boot the Form in the session.
     *
     * @return \Vuravel\Form\Form
     */
    public function pushToSession()
    {
        session()->put($this->vlSessionKey(), array_merge($this->commonSessionAttributes(), [
            'recordKey' => $this->recordKey
        ]));

        return $this;
    }
    
    /**
     * Get a booted instance of the Form class from a request.
     *
     * @param  Illuminate\Http\Request $request (optional)
     * @return \Vuravel\Form\Form
     */
    public function bootFromRequest($r = null) //$r as argument is for MultiForm
    {
        $this->recordKey = $r ? $r->input('id') : request('id');
        $this->store($r ? $r->input('store') : request('store'));
        $this->setParameters($this->getParametersFromRoute($r ?: null));
        return $this->vlBoot(); //should not be pushed to session (done elsewhere, where relevant)
    }

    public function rebootFromSession($sessionObject)
    {
        $this->setCommonRebootAttributes($sessionObject);
        $this->recordKey = $sessionObject['recordKey'] ?? null;
        return $this;
    }

}