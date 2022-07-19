<?php

namespace App\Services\File\Handlers;


use App\Models\File;
use App\Repositories\Video\VideoRepository;
use App\Services\WebcasterPro;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\UploadedFile;

class VideoHandler implements HandlerContract
{
    private VideoRepository $repository;

    public function __construct(VideoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function startService(UploadedFile $file, File $model): File
    {

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

}