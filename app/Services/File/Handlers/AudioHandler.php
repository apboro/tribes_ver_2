<?php

namespace App\Services\File\Handlers;

use App\Models\File;
use App\Repositories\File\FileRepository;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class AudioHandler implements HandlerContract
{
    private $path;
    private FileRepository $repository;

    public function __construct(string $path, FileRepository $repository)
    {
        $this->path = $path;
        $this->repository = $repository;
    }

    /**
     * @param UploadedFile $file
     * @param File $model
     * @param array $procedure
     * @return File
     * @throws \Illuminate\Validation\ValidationException
     */
    public function startService(UploadedFile $file, File $model, array $procedure): File
    {
        $this->validateFile($file);

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

        return $model;
    }

    //Валидация файла

    /**
     * @param $file
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateFile($file)
    {
        $data = ['file' => $file];
        Validator::make($data,[
            'file' => 'mimes:mp3,wav,aac|max:20480',
        ],[
            'file.max' => 'Размер аудио превышает 20MB',
            'file.mimes' => 'Поддерживаемые форматы MP3, WAV, AAC',
        ])->validate();
    }



}