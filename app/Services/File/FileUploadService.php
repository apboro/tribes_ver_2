<?php

namespace App\Services\File;

use App\Models\Course;
use App\Models\File;
use App\Models\User;
use App\Services\File\common\FileCollection;
use App\Services\File\common\FileConfig;
use App\Services\File\common\HandlerContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Log\Logger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class FileUploadService
{
    private $imageTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'application/x-empty'
    ];

//    private $config;
    private $logger;
    private $modelsFile;

    public function __construct(
        FileConfig $config,
        Logger $logger
    )
    {
        $this->modelsFile = new \Illuminate\Database\Eloquent\Collection();
//        $this->modelsFile = new FileCollection();
        $this->config = $config;
        $this->logger = $logger;
    }


    public function procRequest(Request $request): EloquentCollection
    {

        $entity = $request->get('entity',null);
        $entityId = $request->get('entityId',null);
//        $procedure = $request->get('procedure');
//        dd($procedure);
        if($request['course_id']){
            $entity = 'course';
            $entityId = $request['course_id'];
//            $course = Course::find($request['course_id']);
        }

        if(!$entity) {
            throw new Exception('Укажите entity в запросе');
        }

        $handlers = $this->config->getConfig()[$entity];
        if(!$handlers) {
            throw new Exception('Данный entity не сконфигурирован');
        }

        /*if(!in_array([$entity][$procedure], $this->config->getConfig())) {
            $procedure = 'default';
        }*/
//        dd($procedure);
        //todo из $request перебрать все файлы
        //  и обработать их по ихнему mimeType


        $collect = new FileCollection;

        if($request->has('base_64_file')) {
            $collect->add($this->getFileFromBase64($request));
        } else {
            $files = $request->file()['file'];
            if($files instanceof UploadedFile) {
//            dd(1111);
                $collect->add($files);
            } elseif (is_array($files)){
                foreach ($files as $eachFile) {
                    $collect->add($eachFile);
                }
            }
        }

        if($collect->isEmpty()) {
            throw new Exception('Пустой набор файлов');
        }
//dd($collect);
//        $this->processFileCollection($collect, $this->config->get("$entity.$procedure"));
        $this->processFileCollection($collect, $handlers);

        return $this->modelsFile;
    }

    /**
     * @param FileCollection $collect
     * @return void
     */
    protected function processFileCollection(FileCollection $collect, $handlers): void
    {
        /** @var UploadedFile $file */
        foreach ($collect as $file) {
//            dd($file);
            $type = $file->getMimeType();

            if($this->checkImage($type)) {
                $file_type = 'image';
                $config = $handlers[$file_type . '_handler'];

                $this->modelsFile->add($this->procFile($file, $config));
            }
        }
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function procFile(UploadedFile $file, array $config): File
    {
        /** @var HandlerContract $handler */
        $class = $config['handler'];
        unset($config['handler']);
//        dd($config);
        $handler = app()->make($class,$config);

        $model = new File([
            'mime' => null,
            'size' => null,
            'filename' => null,
            'rank' => 0,
            'isImage' => 0,
            'url' => null,
            'hash' => null,
            'uploader_id' => $this->setUploader(),
            'isVideo' => 0,
            'isAudio' => 0,
            'remoteFrame' => null,
            'webcaster_event_id' => null,
            'description' => null,
            'iframe' => null
        ]);
        $model = $handler->startService($file, $model);
//dd($model);
        return $model;
    }

    protected function procCollect(FileCollection $collection, array $entity): EloquentCollection
    {

    }

    /**
     * @param Request $request
     * @return void
     */
    protected function getFileFromBase64(Request $request): UploadedFile
    {
        $image = $request->get('base_64_file');
        //todo выбрать из строки mimeType
        $image = str_replace('data:image/png;base64,', '', $image);
        $mimeType = 'image/png';
        $image = str_replace(' ', '+', $image);
//            dd(base64_decode($image));
        $imageName = 'temp.png';
        if(! Storage::disk('local')->put($imageName, base64_decode($image), $lock = true)) {
            throw new Exception('Не удалось сохранить файл');
        }

        return new UploadedFile(Storage::disk('local')->path($imageName),'temporary_file_name',$mimeType);
    }


    protected function checkImage(string $mimeType): bool
    {
        return in_array($mimeType,$this->imageTypes);
    }

    private function setUploader()
    {
        return env('APP_DEBUG') ?
            $this->uploader_id = User::where('email', 'test-dev@webstyle.top')->first()->id :
            $this->uploader_id = Auth::id();
    }

}