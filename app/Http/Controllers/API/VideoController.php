<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Video\VideoRepositoryContract;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $videoRepo;

    public function __construct(
        VideoRepositoryContract $videoRepo
    )
    {
        $this->videoRepo = $videoRepo;
    }

    public function upload(Request $request)
    {
        $file = $request->files->get('file');
        $filename = $this->videoRepo->storeTempVideo($file);
        return response()->json([
            'path' => $filename
        ]);
    }
}
