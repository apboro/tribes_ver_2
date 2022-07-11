<?php

namespace App\Services\File;

use App\Models\Course;
use App\Models\File;
use App\Services\File\common\FileCollection;
use App\Services\File\common\FileConfig;
use App\Services\File\common\HandlerContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Log\Logger;
use Illuminate\Support\Collection;
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
        $this->modelsFile = new FileCollection();
        $this->config = $config;
        $this->logger = $logger;
    }


    public function procRequest(Request $request): EloquentCollection
    {

        $entity = $request->get('entity',null);
        $entityId = $request->get('entityId',null);
        $procedure = $request->get('procedure');
//        dd($procedure);
        if($request['course_id']){
//            dd(1);
            $entity = 'course';
//            $course = Course::find($request['course_id']);
        }

        if(!$entity) {
            throw new Exception('Укажите entity в запросе');
        }

        if(!$this->config->getConfig()[$entity]) {
            throw new Exception('Данный entity не сконфигурирован');
        }

//        if(!in_array([$entity][$procedure], $this->config->getConfig())) {
//            $procedure = 'default';
//        }
//        dd($procedure);
        //todo из $request перебрать все файлы
        //  и обработать их по ихнему mimeType
        $files = $request->file();
        $collect = new FileCollection;
        if($files instanceof UploadedFile) {
            $collect->add($files);
        } elseif (is_array($files)){
            foreach ($files as $eachFile) {
                $collect->add($eachFile);
            }
        }
        if($request->has('base_64_file')) {
            $collect->add($this->getFileFromBase64($request));
        }

        if($collect->isEmpty()) {
            throw new Exception('Пустой набор файлов');
        }

        $this->processFileCollection($collect, $this->config->get("$entity.$procedure"));

        return $this->modelsFile;
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

    /**
     * @param FileCollection $collect
     * @return void
     */
    protected function processFileCollection(FileCollection $collect, array $config): void
    {
        /** @var UploadedFile $file */
        foreach ($collect as $file) {
            $type = $file->getMimeType();
            if($this->checkImage($type)) {
                $this->modelsFile->add($this->procFile($file, $config));
            }
        }
    }

    protected function checkImage(string $mimeType): bool
    {
        return in_array($mimeType,$this->imageTypes);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function procFile(UploadedFile $file, array $config): File
    {
        /** @var HandlerContract $handler */
        $class = $config['handler'];
        unset($config['handler']);
        $handler = app()->make($class,$config);
        /*'entity' => '',
        'entity_id' => '',*/
        $model = new File([
            'file_name' => '',
            'path' => '',
        ]);
        $model = $handler->startService($file, $model);

        return $model;
    }
}