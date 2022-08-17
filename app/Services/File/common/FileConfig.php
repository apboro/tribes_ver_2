<?php

namespace App\Services\File\common;

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Config;

class FileConfig extends Repository
{
    public function getConfig()
    {
        return Config::get('file_upload');
    }
}