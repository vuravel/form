<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\Textarea;

class Translatable extends Textarea
{
    public $component = 'Translatable';

    protected $locales;

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

    	$this->locales = config('vuravel.locales');
        $this->data([
        	'locales' => $this->locales,
        	'currentLocale' => session('locale')
        ]);
        $this->value([]);
    }

    public function prepareValueForFront($record)
    {
        $this->value = collect($this->locales)->mapWithKeys(function($language, $locale) use($record) {
            return [$locale => $record->record->getTranslation($this->name, $locale, false)];
        })->all();
    }

}
