<?php

namespace App\Services\File\Handlers;

use App\Models\File;
use App\Repositories\Video\VideoRepository;
use App\Services\WebcasterPro;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class VideoHandler implements HandlerContract
{
    private VideoRepository $repository;

    public function __construct(VideoRepository $repository)
    {
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

        $webcaster = new WebcasterPro();
        $resp = $webcaster->uploads($file);

        $ifarme = $this->repository->getVideo($resp->event_id);

        $model['mime'] = $file->getMimeType();
        $model['size'] = $file->getSize();
        $model['isVideo'] = 1;
        $model['filename'] = $resp->file_name;
        $model['url'] = $resp->manifest;
        $model['description'] = json_encode($resp->previews);
        $model['remoteFrame'] = $resp->event_id;
        $model['iframe'] = $ifarme->event->embed;

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
            'file' => 'mimes:mp4,avi|max:102400',
        ],[
            'file.max' => 'Размер видио превышает 100MB',
            'file.mimes' => 'Поддерживаемые форматы MP4, AVI',
        ])->validate();
    }
}