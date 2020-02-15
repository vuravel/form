<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Components\File;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;

class MultiFile extends File
{
    public $multiple = true;

    protected function setAttributeFromRequest($request, $record)
    {
        $oldFiles = $this->value;

        $this->setValue(collect($request->__get($this->name))->map(function($file) use($record){
            return $file instanceOf UploadedFile ? 
                        $this->fileToDB($file, $record) :
                        json_decode($file, true);
        }));

        if($oldFiles)
            collect($oldFiles)->map(function($file){
                if(!in_array(Arr::get($file,'id'), $this->value->pluck('id')->all() ))
                    $this->unlinkFile($file);
            });

        $this->setValue($this->value->count() ? $this->value : null);
    }

    protected function setRelationFromRequest($request, $record)
    {
        if($this->value && $this->value->count()){
            $keepIds = collect($request->input($this->name))
                        ->map(function($file){ return json_decode($file)->id ?? null; })->all();

            $this->value->filter(function($file) use($keepIds) { 
                    return !in_array($file->id ?? '',$keepIds); 
                })->each(function($file) {
                    $this->unlinkFile($file);
                    $file->delete(); //No detach, onDelete('cascade') should give the choice.
                });
        }
        $this->setValue(null);
        
        //Has Many these files will be attached
        if($uploadedFiles = $request->file($this->name))
            $this->setValue(collect($uploadedFiles)->map(function($file) use($record){
                return $this->fileToDB($file, $record);
            }));
    }

}
