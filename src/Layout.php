<?php

namespace Vuravel\Form;

use Vuravel\Form\Components\Html;
use Vuravel\Form\{Component, Form};
use Vuravel\Menu\MenuItems\Traits\HasSubmenu;

class Layout extends Component
{
    use HasSubmenu;
    /**
     * Stores the child components of the layout.
     *
     * @var array
     */
    public $components;

    public function __construct(...$args)
    {
        $this->vlInitialize( class_basename($this) );

        $this->components = $this->getFilteredComponents($args)->values()->all();
    }

    public function prepareComponent($form)
    {
        if($this instanceof Form)
            $form->addValidationRules($this->rules());

        if($form->noMargins ?? false)
            $this->noMargins();

    	collect($this->components)->each(function($component) use($form) {

    		$component->prepareComponent($form);

            $this->prepareClickableChild($component); //added this to extend Flex becoming a Menuitem

    	});

        $this->mountedHook($form);
    }

    /**
     * Removes the default margins applied to rows and columns layouts.
     *
     * @return     self
     */
    public function noMargins()
    {
        return $this->data(['noMargins' => true]);
    }

    public function getFieldComponents($form)
    {
        return collect($this->components)->flatMap( function($component) use ($form) {

            return $component->getFieldComponents($form);

        })->filter();
    }

}