<?php

namespace App\Services\Files;


use Illuminate\Http\Request;

class ImageService {

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function startService($file)
    {
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
//            'file' => 'required|mimes:jpg,png,gif|max:2048'
        
            'file' => 'image'
        ]);


//        dd($this->request['file']);
/*        if($this->request['crop']){
//            dd(1);
            $validated = $this->request->validate([
                'file' => 'required|mimes:jpg,png,gif|max:2048',
                'crop_data' => 'required_if:crop,true',
            ]);
        } else {
//            dd($this->request['file']);
            $validated = $this->request->validate([
                'file' => 'required|mimes:jpg,png,gif|max:2048'
            ]);
        }*/
        return $validated;
    }

}