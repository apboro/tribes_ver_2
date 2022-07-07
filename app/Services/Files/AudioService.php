<?php

namespace App\Services\Files;


use Illuminate\Http\Request;

class AudioService {

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function startService($file)
    {
//        dd($this->request['file']);
        $this->validateAudio();

        return $file;
    }

    private function validateAudio()
    {
        $validated = $this->request->validate([
            'file' => 'required|mimes:mp3,wav,aac|max:100480',
        ]);

        return $validated;
    }


}