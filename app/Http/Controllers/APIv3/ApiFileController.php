<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\File\ApiFileDeleteRequest;
use App\Http\ApiRequests\File\ApiFileUploadRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\File\FileRepositoryContract;
use App\Repositories\Video\VideoRepositoryContract;
use App\Services\File\common\FileEntity;
use App\Services\File\FileUploadService;
use Illuminate\Http\Request;

class ApiFileController extends Controller
{
    private $fileRepo;
    private $videoRepo;
    private $fileUploadService;
    private $fileEntity;

    public function __construct(
        FileRepositoryContract  $fileRepo,
        VideoRepositoryContract $videoRepo,
        FileUploadService       $fileUploadService,
        FileEntity              $fileEntity
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

    public function delete(ApiFileDeleteRequest $request, $id)
    {
        $this->fileRepo->delete($id);
        return ApiResponse::success('Медиа удалено');
    }

    public function upload(ApiFileUploadRequest $request)
    {
        $this->fileEntity->getEntity($request);

        $files = $this->fileUploadService->procRequest($request);

        if ($request['entityModel']) {
            $storedFilesId = [];
            foreach ($files as $file) {
                array_push($storedFilesId, $file['id']);
            }
            $request['entityModel']->attachments()->syncWithoutDetaching($storedFilesId);
        }
        return ApiResponse::common([
            'file' => $files[0]
        ]);
    }
}
