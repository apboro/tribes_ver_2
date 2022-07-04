<?php
namespace App\Repositories\Video;

interface VideoRepositoryContract
{
    public function storeTempVideo($file);
    public function uploadToWebcaster($path, $title);
    public function getVideo($id);
}