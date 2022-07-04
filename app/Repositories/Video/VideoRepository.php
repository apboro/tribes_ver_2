<?php

namespace App\Repositories\Video;

use App\Models\Part;
use App\Services\Webcaster;
use Illuminate\Support\Carbon;
use App\Services\WebcasterPro;

class VideoRepository implements VideoRepositoryContract
{
    protected $webcaster;

    public function __construct()
    {
        $this->webcaster = new Webcaster('fit_univ42', '90673ac23b66bf5a09a5065fa7473a76');
    }

    public function storeTempVideo($file)
    {
        $originalFile = $file->getClientOriginalName();
        $filename = md5($originalFile) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('/videos/temp'), $filename);
        $path = '/videos/temp/' . $filename;

        return $path;
    }

    public function uploadToWebcaster($path, $title)
    {
        // $basePath = (env('APP_ENV') === 'local') ? env('TUNNEL_DOMAIN') : env('APP_URL');

        $resp = $this->webcaster->upload([
            'event[name]' => $title,
            'file[src]' => route('main') . $path,  
            'embed[type]' => 'iframe', 
            'embed[service_id]' => 1113,
        ]);

        return $resp;
    }

    public function streamToWebcaster($title)
    {
        $basePath = (env('APP_ENV') === 'local') ? env('TUNNEL_DOMAIN') : env('APP_URL');
        // dd($basePath, Carbon::now()->addDays(1)->format('Y-m-d h:i:s'));
        $resp = $this->webcaster->upload([
            'event[name]' => $title,
            // 'file[src]' => 'https://' . $basePath . $path,
            'event[start_at]' => Carbon::now()->addDays(1)->format('Y-m-d h:i:s'),
            'embed[service_id]' => 1113,
        ]);

        return $resp;
    }

    public function getChannelsWebcaster()
    {
        $basePath = (env('APP_ENV') === 'local') ? env('TUNNEL_DOMAIN') : env('APP_URL');
        $resp = $this->webcaster->getChannels();

        return $resp;
    }

    public function setThumbWebcaster($event_id, $thumb_id)
    {
        $resp = $this->webcaster->setThumb([
            'event_id' => $event_id,
            'main_thumbnail_id' => $thumb_id,
        ]);

        return $resp;
    }

    public function getVideoThumb($event_id)
    {
        $resp = $this->webcaster->getThumb([
            'event_id' => $event_id,
        ]);
        return $resp;
    }

    public function getVideo($id) 
    {
        $resp = $this->webcaster->getEventData([
            'id' => $id,
            'embed[type]' => 'iframe',
            'embed[service_id]' => 1113
        ]);
        return $resp;
    }
}