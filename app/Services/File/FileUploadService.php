<?php

namespace App\Services\File;

use App\Models\File;
use App\Models\User;
use App\Services\File\common\FileCollection;
use App\Services\File\common\FileConfig;
use App\Services\File\common\HandlerContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
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

    private $logger;
    public $modelsFile;
    private $request;

    public function __construct(
        FileConfig $config,
        Logger $logger
    )
    {
        $this->modelsFile = new \Illuminate\Database\Eloquent\Collection();
        $this->config = $config;
        $this->logger = $logger;

    }

    /**
     * @param $request
     * @return EloquentCollection
     */
    public function procRequest($request): EloquentCollection
    {
        $this->request = $request;

        if (isset($request['entity'])){
            $entity = $request['entity'];
        } else {
            throw new Exception('Укажите entity в запросе');
        }

        $handlers = $this->config->getConfig()[$entity];

        if(!$handlers) {
            throw new Exception('Данный entity не сконфигурирован');
        }

        //  из $request перебрать все файлы
        //  и обработать их по ихнему mimeType
        $collect = new FileCollection;

        if(isset($request['base_64_file'])) {
            $collect->add($this->getFileFromBase64($request));
        } else {
            if (isset( $request['file'] )) {
                $files = $request['file'];
                $collect->addFiles($files);
            } else {
                throw new Exception('Файл не найден');
            }
        }

        if($collect->isEmpty()) {
            throw new Exception('Пустой набор файлов');
        }

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

        $procedure = [];
        if (isset($config['procedure'])) {
            foreach ($config['procedure'] as $proc) {
                switch ($proc) {
                    case 'crop':
                        $procedure['crop'] = $this->request['cropData'];
                        break;
                    case 'watermark':
                        $procedure['watermark'] = asset('images/watermarks/test-watermark.png');
                        break;
                }

            }
        }

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

        return $model;
    }

    /**
     * @param $request
     * @return UploadedFile
     */
    protected function getFileFromBase64($request): UploadedFile
    {
        $image = $request['base_64_file'];

        $image = str_replace('data:image/png;base64,', '', $image);
        $mimeType = 'image/png';
        $image = str_replace(' ', '+', $image);
        $imageName = 'temp.png';
        if(! Storage::disk('local')->put($imageName, base64_decode($image), $lock = true)) {
            throw new Exception('Не удалось сохранить файл');
        }

        return new UploadedFile(Storage::disk('local')->path($imageName),'temp.png',$mimeType, null, true);
    }

    /**
     * @param string $mimeType
     * @return bool
     */
    protected function checkImage(string $mimeType): bool
    {
        return in_array($mimeType, $this->imageTypes);
    }

    /**
     * @param string $mimeType
     * @return bool
     */
    protected function checkAudio(string $mimeType): bool
    {
        return in_array($mimeType, $this->audioTypes);
    }

    /**
     * @param string $mimeType
     * @return bool
     */
    protected function checkVideo(string $mimeType): bool
    {
        return in_array($mimeType, $this->videoTypes);
    }

    /**
     * @return int|string|null
     */
    private function setUploader()
    {
        return env('APP_DEBUG') ?
            $this->uploader_id = User::where('email', 'test-dev@webstyle.top')->first()->id :
            $this->uploader_id = Auth::id();
    }

}