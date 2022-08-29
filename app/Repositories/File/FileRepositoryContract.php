<?php


namespace App\Repositories\File;
use Illuminate\Http\Request;

interface FileRepositoryContract
{
//    public function storeFile($file);
    public function saveImageForSeeder($path);
    public function get($file);
    public function delete($file);
}
