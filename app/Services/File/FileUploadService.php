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
    public $videoTypes = [
        'video/mp4',
        'video/x-m4v'
    ];

    public $audioTypes = [
        'audio/mp4',
        'audio/aac',
        'audio/mpeg',
    ];

//    private $config;
    private $logger;
    private $modelsFile;
    private $request;

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


    public function procRequest($request): EloquentCollection
    {
        $this->request = $request;
//        dd($request);

//        dd($request instanceof Request);
//        $entity = $request->get('entity',null);
//        dd($request['entity']);
//        $entity = $request['entity'];
//        dd($entity);

        if (isset($request['entity'])){
            $entity = $request['entity'];
        } else {
            throw new Exception('Укажите entity в запросе');
        }

//        dd($request);
        /*if(!$entity) {
            throw new Exception('Укажите entity в запросе');
        }*/

        $handlers = $this->config->getConfig()[$entity];

        if(!$handlers) {
            throw new Exception('Данный entity не сконфигурирован');
        }

        /*if(!in_array([$entity][$procedure], $this->config->getConfig())) {
            $procedure = 'default';
        }*/
        //todo из $request перебрать все файлы
        //  и обработать их по ихнему mimeType

        $collect = new FileCollection;

        /*if($request->has('base_64_file')) {
            $collect->add($this->getFileFromBase64($request));
        } else {
            if ($request->file()['file']) {
                $files = $request->file()['file']; // TODO ПРОВЕРИТЬ НА СУЩЕСТВОВАНИЕ
                $collect->addFiles($files);
            } else {
                throw new Exception('Файл не найден');
            }

        }*/
        if(isset($request['base_64_file'])) {
            $collect->add($this->getFileFromBase64($request));
        } else {
            if (isset( $request['file'] )) {
                $files = $request['file']; // TODO ПРОВЕРИТЬ НА СУЩЕСТВОВАНИЕ
                $collect->addFiles($files);
            } else {
                throw new Exception('Файл не найден');
            }

        }

        if($collect->isEmpty()) {
            throw new Exception('Пустой набор файлов');
        }


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
//            dd($handlers);
            $type = $file->getMimeType();

            if($this->checkImage($type)) {
                $file_type = 'image';
                $config = $handlers[$file_type . '_handler'];
            }
            elseif($this->checkAudio($type)){
                $file_type = 'audio';
                $config = $handlers[$file_type . '_handler'];
            }
            elseif($this->checkVideo($type)){
                $file_type = 'video';
                $config = $handlers[$file_type . '_handler'];
            } else {
                throw new Exception('Неизвестный тип файла');
            }
//            dd($handlers);
            $this->modelsFile->add($this->procFile($file, $config));
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
//        $procedure = isset($config['procedure']) ? $config['procedure'] : null;


        $procedure =[];
        if (isset($config['procedure'])) {
            foreach ($config['procedure'] as $proc) {
//                dd($proc);
                if ($proc == 'crop'){
                    $procedure['crop'] = $this->request['cropData'];
                }
            }
        }



//        dd($procedure);


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
        $model = $handler->startService($file, $model, $procedure);
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
    protected function getFileFromBase64($request): UploadedFile
    {
        $image = $request['base_64_file'];
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
        return in_array($mimeType, $this->imageTypes);
    }
    protected function checkAudio(string $mimeType): bool
    {
        return in_array($mimeType, $this->audioTypes);
    }
    protected function checkVideo(string $mimeType): bool
    {
        return in_array($mimeType, $this->videoTypes);
    }

    private function setUploader()
    {
        return env('APP_DEBUG') ?
            $this->uploader_id = User::where('email', 'test-dev@webstyle.top')->first()->id :
            $this->uploader_id = Auth::id();
    }

}