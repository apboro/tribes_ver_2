<?php

namespace App\Services\File\Handlers;


use App\Services\File\common\HandlerContract;

class VideoHandler implements HandlerContract
{

    public function startService($file)
    {
        dd($file);
    }

}