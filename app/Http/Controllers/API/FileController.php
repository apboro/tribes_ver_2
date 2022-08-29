<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\File\FileRepositoryContract;
use App\Services\File\common\FileEntity;
use App\Repositories\Video\VideoRepositoryContract;
use App\Services\File\FileUploadService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $fileRepo;
    private $videoRepo;
    private $fileUploadService;
    private $fileEntity;

    public function __construct(
        FileRepositoryContract $fileRepo,
        VideoRepositoryContract $videoRepo,
        FileUploadService $fileUploadService,
        FileEntity $fileEntity
    )
    {
       $this->fileRepo = $fileRepo;
       $this->videoRepo = $videoRepo;
       $this->fileUploadService = $fileUploadService;
       $this->fileEntity = $fileEntity;
    }

    public function get(Request $request)
    {
        return $this->fileRepo->get($request['id']);
    }

    public function delete(Request $request)
    {
        return $this->fileRepo->delete($request['id']);
    }

    public function upload(Request $request)
    {
        $this->fileEntity->getEntity($request);

        $files = $this->fileUploadService->procRequest($request);

        if($request['entityModel']){
            $storedFilesId = [];
            foreach ($files as $file){
                array_push($storedFilesId, $file['id']);
            }
            $request['entityModel']->attachments()->syncWithoutDetaching($storedFilesId);
        }

        return response()->json([
            "status" => "ок",
            "message" => "Загрузка удалась",
            "file" => $files[0],
        ]);
    }
}
