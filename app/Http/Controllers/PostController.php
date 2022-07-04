<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

use Exception;

class PostController extends Controller
{
    public function saveVideo(Request $request)
    {

        if ($request->isMethod('post')) {

            $hash = md5(time());
            $dir = storage_path('app/public/video');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $path = $dir . '/' .  $hash . '.mp4';
            $request->f ? file_put_contents($path, file_get_contents($request->f) ?? null) : null;
            $video = route('main') . '/storage/video/' . $hash . '.mp4';

            Http::post(env('TELEGRAM_BASE_URL') . '/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendVideo', [
                    'chat_id'        => '-722874807',
                    'video'          => $video,
            ]);
        }
        
        return view('common.post.video');
    }
}
