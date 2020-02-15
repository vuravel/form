<?php

namespace Vuravel\Form\Components;

use Illuminate\Http\UploadedFile;
use Vuravel\Form\Components\Traits\UploadsFiles;
use Vuravel\Form\Field;

class File extends Field
{
    use UploadsFiles;

    public $component = 'File';

    protected $defaultSchema = false;

    /**
     * Use this flag if your files table has this default schema: id, name, path, mime_type, size.
     *
     * @return     self 
     */
    public function defaultSchema()
    {
        $this->defaultSchema = true;
        return $this;
    }

    public function customSetValueFromDB($record)
    {
        if($this->defaultSchema){

            $this->isAttribute = true;
            $this->fillsBeforeSave = true;
            $this->fillsAfterSave = false;

            $this->setValue($record->record->toArray());
        }else{
            $this->normalSetValueFromDB($record);
        }
    }

    protected function setAttributeFromRequest($request, $record)
    {
        $oldFile = $this->value;

        if( ($uploadedFile = $request->__get($this->name)) 
                && ($uploadedFile instanceOf UploadedFile)){

            $this->unlinkFile($oldFile);
            $this->setValue($this->fileToDB($uploadedFile, $record));

        }elseif(!$request->__get($this->name)){
            $this->unlinkFile($oldFile);
            $this->setValue(null);
        }
    }

    protected function customFillAttribute($record)
    {
        if($this->defaultSchema){
            collect($this->value)->each(function($v, $k) use($record) {
                if($k != $record->record->getKeyName() && $record->isAttribute($k))
                    $record->fillAttribute($k, $v);
            });
        }else{
            $record->fillAttribute($this->name, $this->value);
        }
    }

    protected function setRelationFromRequest($request, $record)
    {
        $oldFile = $this->value;
        
        if( ($uploadedFile = $request->__get($this->name)) 
                && ($uploadedFile instanceOf UploadedFile)){

            $this->unlinkFile($oldFile);
            $oldFile && $oldFile->delete();
            $this->setValue($this->fileToDB($uploadedFile, $record));

        }else{
            if(!$request->__get($this->name) && $oldFile){
                $this->unlinkFile($oldFile);
                $oldFile->delete();
            }
            $this->setValue(null);
        }
    }

}
