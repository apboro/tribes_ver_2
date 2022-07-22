<?php

namespace App\Services\File\common;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * @method addFile(Mixed_ $file)
 * @method UploadedFile current()
 */

class FileCollection extends \Illuminate\Support\Collection
{

    public function addFiles($files)
    {
        if(is_array($files)){
            foreach ($files as $file){
                if($this->checkFileType($file)){
                    $this->add($file);
                }
            }
        } else {
            if($this->checkFileType($files)){
                $this->add($files);
            }
        }

        return true;
    }

    private function checkFileType($file) : bool
    {
        return $file instanceof UploadedFile;
    }
}