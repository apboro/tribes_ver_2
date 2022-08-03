<?php

namespace App\Services\File\Handlers;


use App\Models\File;
use App\Repositories\File\FileRepository;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AudioHandler implements HandlerContract
{

    private $path;
    private FileRepository $repository;

    public function __construct(string $path, FileRepository $repository)
    {
        $this->path = $path;
        $this->repository = $repository;
    }

    public function startService(UploadedFile $file, File $model, array $procedure): File
    {
//        dd($this->path);
        $hash = $this->repository->setHash($file);
        $filename = $hash . '.' . $file->guessClientExtension();

        $url = $this->repository->storeFileNew($file, $this->path, $filename);

        $model['mime'] = $file->getMimeType();
        $model['size'] = $file->getSize();
        $model['isAudio'] = 1;
        $model['filename'] = $filename;
        $model['url'] = $url;
        $model['hash'] = $hash;

        $model->save();
//        dd($file);
        return $model;
    }

    private function validateAudio()
    {
        $validated = $this->request->validate([
            'file' => 'required|mimes:mp3,wav,aac|max:100480',
        ]);

        return $validated;
    }


}