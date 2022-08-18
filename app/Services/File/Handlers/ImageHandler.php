<?php /** @noinspection PhpHierarchyChecksInspection */

namespace App\Services\File\Handlers;

use App\Models\File;
use App\Repositories\File\FileRepository;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;

class ImageHandler implements HandlerContract
{
    private $path;
    private FileRepository $repository;
    private $errors = [];

    public function __construct(string $path, FileRepository $repository)
    {
        $this->path = $path;
        $this->repository = $repository;
    }

    /**
     * @param UploadedFile $file
     * @param File $model
     * @param array $procedure
     * @return File
     * @throws \Illuminate\Validation\ValidationException
     */
    public function startService(UploadedFile $file, File $model, array $procedure): File
    {
        $this->validateFile($file);

        $fileProcessed = Image::make($file)->encode('jpg', 75);

        $this->compressionFile($fileProcessed);

//        dd($fileProcessed);

        //Обрабатываем картинку (crop, resize и т.д.)
        if ($procedure) {
            foreach ($procedure as $key => $proc) {
                switch ($key) {
                    case 'crop':
                        $fileProcessed = $this->crop($proc, $fileProcessed);
                        break;
                    case 'watermark':
                        $fileProcessed = $this->watermark($proc, $fileProcessed);
                        break;
                }
            }
        }

        $fileProcessed = $this->saveFileProcessed($fileProcessed);

        $hash = $this->repository->setHash($fileProcessed);
        $filename = $hash . '.' . $fileProcessed->guessClientExtension();

        $url = $this->repository->storeFileNew($fileProcessed, $this->path, $filename);

        $model['mime'] = $fileProcessed->getMimeType();
        $model['size'] = $fileProcessed->getSize();
        $model['isImage'] = 1;
        $model['filename'] = $filename;
        $model['url'] = $url;
        $model['hash'] = $hash;

        $model->save();

        return $model;
    }

    /**
     * @param $crop
     * @param $fileProcessed
     * @return mixed
     */
    private function crop($crop, $fileProcessed)
    {
        $dimensions = explode('|', $crop);

        $fileProcessed->crop((int)$dimensions[2], (int)$dimensions[3], $x = (int)$dimensions[0], $y = (int)$dimensions[1]);

        return $fileProcessed;
    }

    /**
     * @param $crop
     * @param $fileProcessed
     * @return mixed
     */
    private function watermark($crop, $fileProcessed)
    {
        $fileProcessedSize = $fileProcessed->getSize();
        $fileProcessedWidth = $fileProcessedSize->width;
        $fileProcessedHeight = $fileProcessedSize->height;

        $watermark = Image::make($crop);

        if($fileProcessedWidth > $fileProcessedHeight) {
            //делаем высоту вотермарки 40% от высоты картинки
            $watermark->resize(null, $fileProcessedHeight/100*40, function ($constraint){
                $constraint->aspectRatio();
            });
        } else {
            //делаем ширину вотермарки 40% от ширины картинки
            $watermark->resize($fileProcessedWidth/100*40, null, function ($constraint){
                $constraint->aspectRatio();
            });
        }

        $fileProcessed->insert($watermark, 'bottom-right');

        return $fileProcessed;
    }

    /**
     * @param $fileProcessed
     * @return UploadedFile
     */
    private function saveFileProcessed($fileProcessed)
    {
        if (!file_exists(storage_path('app/public/temp/'))) {
            mkdir(storage_path('app/public/temp/'), 0755, true);
        }

        $fileProcessed->save(storage_path('app/public/temp/') . 'temp.jpg', 75, 'jpg');

        $file = new UploadedFile($fileProcessed->dirname . '/' . $fileProcessed->basename, $fileProcessed->basename, $fileProcessed->mime, null, true);
        return $file;
    }

    private function compressionFile($fileProcessed)
    {
        //1920×1080
//        $width = $fileProcessed->getSize()->width;
//        $height = $fileProcessed->getSize()->height;
        $fileProcessed->resize(1920, 1080, function ($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $fileProcessed;
    }

    //Валидация файла

    /**
     * @param $file
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateFile($file)
    {
        $data = ['file' => $file];

        Validator::make($data,[
            'file' => 'mimes:jpg,jpeg,png,gif',
        ],[
            'file.mimes' => 'Поддерживаемые форматы JPG, JPEG, PNG, GIF',
        ])->validate();
    }

}