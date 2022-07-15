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

    public function __construct(
        FileRepositoryContract $fileRepo,
        VideoRepositoryContract $videoRepo,
        FileUploadService $fileUploadService
    )
    {
       $this->fileRepo = $fileRepo;
       $this->videoRepo = $videoRepo;
       $this->fileUploadService = $fileUploadService;
    }

    public function delete(Request $request){
        $file = File::find($request['id']);
        if(!$file->isVideo){
            unlink(storage_path('app/public/' . str_replace('/storage/', '', $file->url)));
        }

        $file->delete();
    }

    public function get(Request $request)
    {
        $file = File::find($request['id']);

        if(!$file) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не найдено',
                'details' => 'Файл не найден или у вас нет прав для редактирования',
            ]);
        } else {
            return response()->json([
                "status" => "ok",
                "details" => "",
                'file' => $file
            ]);
        }
    }

    public function upload(Request $request)
    {

//        dd($request['course_id']);
//        $f = $this->fileUploadService->prepareStoreFile();

//        return 'audio loaded';

//        $this->fileRepo->storeFile(
//            $this->FileUploadService->storeFiles() // [1, 2, 44]
//        );
//
//        $this->fileRepo->storeFile(
//            $this->FileUploadService->storeFiles() // [1, 2, 44]
//        );
//        $FileUploadService = new FileUploadService($request);
//        $FileUploadService->init();




//        dd(mime_content_type($request['file']));
//dd($request->all());


/*
        if($request['base_64_file']){
            $image = $request['base_64_file'];  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'temp.png';

            FileFacade::put($imageName, base64_decode($image));

            $file = $this->pathToUploadedFile($imageName, false);

        } else {
            $file = $request['file'];
        }

        if(!$file){
            return response()->json([
                'status' => 'error',
                'message' => 'Нет прикрепленного файла'
            ]);
        }

        $mime = $file->getMimeType();

        $title = $request['title'] ?? explode('.', $file->getClientOriginalName() ?? 'Без названия.jpg')[0] ;

        if(in_array($mime, $this->fileRepo->imageTypes)){
            $decoded = json_decode($request['crop']) ?? new \stdClass();
            $fileData['file'] = $file;
            $fileData['crop'] = $decoded->isCrop ?? false;
            $fileData['cropData'] = $decoded->cropData ?? null;
            $f = $this->fileRepo->storeFile($fileData);
        } elseif (in_array($mime, $this->fileRepo->videoTypes)){
//            $path = $this->videoRepo->storeTempVideo($file);
//            $resp = $this->videoRepo->uploadToWebcaster($path, $title);

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

        } elseif (in_array($mime, $this->fileRepo->audioTypes)){
            $fileData['file'] = $file;
            $f = $this->fileRepo->storeFile($fileData);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Загрузка не удалась",
                "details" => "Файл не поддерживается для загрузки.",
            ]);
        }*/
//dd($f);
//        dd($f);

//        dd($request->storedFiles);
//dd($request['course_id']);

//        dd($request->storedFiles[0]);
//        $filesId = $request->storedFiles;

//        dd($request);
        $files = $this->fileUploadService->procRequest($request);
//        dd($files);
//dd($files);
        /*if($request['course_id']){
            $course = Course::find($request['course_id']);
        }
        if($course){
            $course->attachments()->sync($request->storedFilesId);
        }*/

        return response()->json([
            "status" => "ок",
            "message" => "Загрузка удалась",
            "file" => $files,
        ]);
    }

    private function is_base64($s)
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
    }
}
