<?php

namespace App\Services\File\common;

use App\Models\File;
use Illuminate\Http\UploadedFile;

interface HandlerContract
{
    public function startService(UploadedFile $file, File $model, array $procedure): File;
}