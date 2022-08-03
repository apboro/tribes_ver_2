<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\File;
use App\Services\WebcasterPro;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use PHPUnit\Exception;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\File as FileFacade;
use Symfony\Component\HttpFoundation\File\File as F;
use App\Repositories\File\FileRepositoryContract;
use App\Services\File\common\FileEntity;
use App\Repositories\Video\VideoRepository;
use App\Repositories\Video\VideoRepositoryContract;
use App\Services\File\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    /*private function is_base64($s)
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }

    public static function pathToUploadedFile( $path, $public = false )
    {
        $name = FileFacade::name( $path );

        $extension = FileFacade::extension( $path );

        $originalName = $name . '.' . $extension;

        $mimeType = FileFacade::mimeType( $path );

        $size = FileFacade::size( $path );

        $error = null;

        $test = $public;

        $object = new UploadedFile($path, $originalName, $mimeType, $error, false);

        return $object;
    }*/
}
