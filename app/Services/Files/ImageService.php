<?php

namespace App\Services\Files;


use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ImageService {

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function startService(UploadedFile $file)
    {
        $this->file = $file;
//        dd($file->getClientOriginalName());
//        dd($this->request['file']);
//        dd($this->request['file']);
//        dd($this->request['crop']);
//        $this->validateImage();

//        dd($file);
        return $file;
    }

    private function validateImage()
    {
//        dd($this->request['file']);
        $validated = $this->file->validate([
            'file' => 'required|mimes:jpg,png,gif|max:2048'

//            'file' => 'image'
        ]);
    }



/*
    public function startService($file)
    {
//        dd($file->getClientOriginalName());
//        dd($this->request['file']);
//        dd($this->request['file']);
//        dd($this->request['crop']);
        $this->validateImage();

//        dd($file);
        return $file;
    }

    private function validateImage()
    {
//        dd($this->request['file']);
        $validated = $this->request->validate([
            'file' => 'required|mimes:jpg,png,gif|max:2048'
        
//            'file' => 'image'
        ]);

        return $validated;
    }*/

}