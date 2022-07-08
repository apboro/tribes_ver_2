<?php

namespace App\Services\Files;

use App\Models\File;
use App\Models\User;
use App\Repositories\File\FileRepositoryContract;
use App\Repositories\Video\VideoRepositoryContract;
use App\Services\Files\AudioService;
use App\Services\Files\ImageService;
use App\Services\Files\VideoService;
use App\Services\WebcasterPro;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File as FileFacade;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    private $fileRepo;

    public $imageTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'application/x-empty'
    ];

    public $videoTypes = [
        'video/mp4',
        'video/x-m4v'
    ];

    public $audioTypes = [
        'audio/mp4',
        'audio/aac',
        'audio/mpeg',
    ];

    private $fields = [
        'mime',
        'size',
        'filename',
        'rank',
        'description',
        'isImage',
        'isVideo',
        'isAudio',
        'url',
        'hash',
        'uploader_id',
    ];


    private $audioService;
    private $imageService;
    private $videoService;
    private $request;
    private $videoRepo;

    private $storedCollection;
    private $storedCollectionId;

    public function __construct(
        FileRepositoryContract $fileRepo,
        VideoRepositoryContract $videoRepo,
        AudioService $audioService,
        ImageService $imageService,
        VideoService $videoService,
        Request $request
    )
    {
        $this->fileRepo = $fileRepo;
        $this->videoRepo = $videoRepo;
        $this->audioService = $audioService;
        $this->imageService = $imageService;
        $this->request = $request;

        $this->storedCollection = collect();
        $this->storedCollectionId = collect();

        $this->prepareStoreFile();
    }

    public function prepareStoreFile()
    {
        $files = $this->request->file('file');

        if( $this->request->has('base_64_file')){
            $this->checkBase64($this->request->base_64_file);

            $image = $this->request['base_64_file'];  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            Storage::disk('tpm_file')->put('temp.png', base64_decode($image));
            $pathTempFile = Storage::disk('tpm_file')->path('temp.png');
            $file = $this->pathToUploadedFile($pathTempFile, false);

            $this->prepareFile($file);

        }
        elseif (is_array($files)){
            foreach ($files as $file){
                $this->prepareFile($file);
            }
        } elseif($files) {
            $this->prepareFile($files);
        }


        $this->request->storedFiles = $this->readyFiles();
        $this->request->storedFilesId = $this->readyFilesId();
    }



    ////////////////////////////////////////////////////////////////////
    public static function pathToUploadedFile( $path, $public = false )
    {
        $name = FileFacade::name( $path );

        $extension = FileFacade::extension( $path );

        $originalName = $name . '.' . $extension;

        $mimeType = FileFacade::mimeType( $path );

        $error = null;

        $object = new UploadedFile($path, $originalName, $mimeType, $error, false);

        return $object;
    }
    ////////////////////////////////////////////////////////////////////

    private function readyFiles()
    {
        return $this->storedCollection;
    }

    private function readyFilesId()
    {
        return $this->storedCollectionId;
    }

    private function prepareFile(UploadedFile $file) : void
    {
//        dd($file);
        $isImage = $this->isImage($file);
        $isVideo = $this->isVideo($file);
        $isAudio = $this->isAudio($file);

        $this->setMime($file);
        $this->setSize($file);
        $this->setRank($file);
        $this->setDescription();
        $this->setUploader();
        $this->setExtension($file);
        $this->setFilename($file);

        if ($isAudio) {
            $type = 'audio';
            $file = $this->audioService->startService($file);
        } elseif ($isImage) {
            $type = 'image';
            $file = $this->imageService->startService($file);
        } elseif ($isVideo) {
//            $type = 'video';
//            $file = $this->videoService->startService($file);

            $webcaster = new WebcasterPro();
            $resp = $webcaster->uploads($file);

            $ifarme = $this->videoRepo->getVideo($resp->event_id);

            $file = File::create([
                'isVideo' => true,
                'filename' => $resp->file_name,
                'size' => 0,
                'mime' => $this->mime,
                'description' => json_encode($resp->previews),
                'uploader_id' => Auth::user()->id,
                'url' => $resp->manifest,
                'remoteFrame' => $resp->event_id,
                'iframe' => $ifarme->event->embed
            ]);
        }

        if(!$isVideo) {
            $this->storeFile($file, $type, true);

            $file = $this->storeFileToDB();
        }

        $this->storedCollection->push($file);
        $this->storedCollectionId->push($file->id);
    }

    private function setMime($file)
    {
        return $this->mime = $file->getMimeType();
    }

    private function setSize($file)
    {
        return $this->size = $file->getSize();
    }

    private function setRank($file)
    {
        return $this->rank = 0;
    }
    private function setDescription()
    {
        return $this->description = isset($this->request['description']) ? $this->request['description'] : 'File';
    }
    private function setUploader()
    {
        return env('APP_DEBUG') ?
            $this->uploader_id = User::where('email', 'test-dev@webstyle.top')->first()->id :
            $this->uploader_id = Auth::id();
    }

    private function checkBase64($file) : bool
    {
        $file = str_replace('data:image/png;base64,', '', $file);

        return base64_encode(base64_decode($file)) === $file ?? false;
    }

    private function isAudio($file)
    {
        return $this->isAudio = in_array($file->getMimeType(), $this->fileRepo->audioTypes);
    }

    private function isImage($file)
    {

        return $this->isImage = in_array($file->getMimeType(), $this->fileRepo->imageTypes);
    }

    private function isVideo($file)
    {
        return $this->isVideo = in_array($file->getMimeType(), $this->videoTypes);
    }





    //////////////////////////////////////////////////
    ///

    private function setUrl($path)
    {
        return $this->url = '/storage/' . $path . $this->filename;
    }
    private function setHash($file)
    {
        return $this->hash = md5($file . Carbon::now()) ;
    }

    private function setFilename($file)
    {
        return $this->filename = $this->setHash($file) . '.' . $this->extension;
    }

    private function setExtension($file)
    {
        return $this->extension = $file->guessClientExtension();
    }

    private function storeFile($file, $type, $local = false)
    {
        $absolutPath = $type . '/' . Carbon::now()->format('d_m_y') . '/';
        $this->setUrl($absolutPath);

        $path = $local ? 'public/' . $absolutPath : storage_path('app/public/') . $absolutPath;

        if (!file_exists(storage_path('app/public/') . $absolutPath)) {
            mkdir(storage_path('app/public/') . $absolutPath, 0755, true);
        }

        $file->storeAs($path, $this->setFilename($file));
    }

    private function collectFileData()
    {
        $data = [];
        foreach ($this->fields as $field){
            $data[$field] = $this->$field;
        }
        return collect($data);
    }

    public function storeFileToDB()
    {
        $file = File::create($this->collectFileData()->only($this->fields)->toArray());

//        dd($file);

        return $file;
    }


}

