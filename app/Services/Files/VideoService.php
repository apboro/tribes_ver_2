<?php

namespace App\Services\Files;

use App\Services\WebcasterPro;
use App\Repositories\Video\VideoRepositoryContract;
use App\Models\File;

class VideoService {

    private $videoRepo;

    public function __construct(
        VideoRepositoryContract $videoRepo
    )
    {
        $this->videoRepo = $videoRepo;
    }

    public function startService($file)
    {
        $webcaster = new WebcasterPro();
        $resp = $webcaster->uploads($file);

        $ifarme = $this->videoRepo->getVideo($resp->event_id);

        $f = File::create([
            'isVideo' => true,
            'filename' => $resp->file_name,
            'size' => 0,
            'mime' => $mime,
            'description' => json_encode($resp->previews),
            'uploader_id' => Auth::user()->id,
            'url' => $resp->manifest,
            'remoteFrame' => $resp->event_id,
            'iframe' => $ifarme->event->embed
        ]);
    }

}