<?php 
namespace Vuravel\Form\Components\Traits;

trait UploadsFiles {

    /**
     * This specifies extra attributes (constant columns/values) to add to the model.
     *
     * @param      array  $attributes  The extra attributes to fill
     *
     * @return     self  
     */
    public function extraAttributes($attributes)
    {
        $this->extraAttributes = $attributes;
        return $this;
    }

    protected function fileToDB($file, $record)
    {
        $this->storeFile($file, $record);

        return array_merge([
            config('vuravel.files_attributes.id') => $this->isAttribute ? uniqid() : null,
            config('vuravel.files_attributes.name') => $file->getClientOriginalName(),
            config('vuravel.files_attributes.path') => $this->publicHashPath($file, $record),
            config('vuravel.files_attributes.mime_type') => $file->getClientMimeType(),
            config('vuravel.files_attributes.size') => $file->getClientSize()
        ], $this->extraAttributes);
    }

    protected function storeFile($file, $record)
    {
        $file->store($this->storagePath($record));
    }

    protected function storagePath($record)
    {
        return 'public/'.$this->getPathFromRecord($record);
    }

    protected function publicPath($record)
    {
        return 'storage/'.$this->getPathFromRecord($record);
    }

    protected function getPathFromRecord($record)
    {
        return $record->getStoragePath($this->defaultSchema ? 
                                            config('vuravel.files_attributes.path') : 
                                            $this->name);
    }

    protected function unlinkFile($file)
    {
        if($file){
            if(file_exists($path = $this->realPath(
                $file[config('vuravel.files_attributes.path')] ?? $file->{config('vuravel.files_attributes.path')}))){
                unlink($path);
                if(($this->withThumbnail ?? false) && file_exists(thumb($path)))
                    unlink(thumb($path));
            }
        }
    }

    protected function realPath($dbPath)
    {
        return storage_path('app/public'.substr($dbPath, 7));
    }

    protected function publicHashPath($file, $record)
    {
        return $this->publicPath($record).'/'.$file->hashName();
    }

}