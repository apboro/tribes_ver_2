<?php /** @noinspection PhpHierarchyChecksInspection */

namespace App\Services\File\Handlers;


use App\Models\File;
use App\Repositories\File\FileRepository;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ImageHandler implements HandlerContract
{

    private $path;
    private FileRepository $repository;

    /*private $fields = [
        'url',
        'hash',
        'filename'
    ];*/

    public function __construct(string $path, FileRepository $repository)
    {

//        $this->path = Storage::disk('public')->path($path);
        $this->path = $path;
        $this->repository = $repository;
    }


    public function startService(UploadedFile $file, File $model, array $procedure): File
    {
//        dd($file);
        foreach ($procedure as $key => $proc) {
//            dd($procedure);
            switch ($key) {
                case 'crop':
                    $fileNew = $this->crop($proc, $file);
                    break;
                case 'watermark':
                    $fileNew = $this->watermark($proc, $file);
                    break;
            }

        }
        //как-то обрабатываем картинку (crop, resize и т.д.)
        //todo
        //репозиторий делает сохранение файла в нужную папку и возвращает полное имя файла $file->storeAs($this->path.$file->getClientOriginalName());


        $hash = $this->repository->setHash($fileNew);
        $filename = $hash . '.' . $fileNew->guessClientExtension();
//        dd($filename);
//        $url = $this->repository;

        $url = $this->repository->storeFileNew($fileNew, $this->path, $filename);
//dd($url);
        $model['mime'] = $fileNew->getMimeType();
        $model['size'] = $fileNew->getSize();
        $model['isImage'] = 1;
        $model['filename'] = $filename;
        $model['url'] = $url;
        $model['hash'] = $hash;

        $model->save();
//        dd($file);
        return $model;
    }


    ////////////////////////////////////////////////////////

    public function crop($crop, $file)
    {
        $image = Image::make($file)->encode('jpg', 100);

        $dimensions = explode('|', $crop);
        $image->crop((int)$dimensions[2], (int)$dimensions[3], $x = (int)$dimensions[0], $y = (int)$dimensions[1]);

        $image->save(storage_path('app/public/temp/') . 'temp.png', 100, 'png');

        $f = new UploadedFile($image->dirname . '/' . $image->basename, $image->basename, $image->mime);

        return $f;
    }

    public function watermark($crop, $file)
    {
        $image = Image::make($file)->encode('jpg', 100);

        $image->insert($crop, 'center');

        $image->save(storage_path('app/public/temp/') . 'temp.png', 100, 'png');

        $f = new UploadedFile($image->dirname . '/' . $image->basename, $image->basename, $image->mime);

        return $f;
    }





    private function validateImage()
    {
//        dd($this->request['file']);
        $validated = $this->request->validate([
            'file' => 'required|mimes:jpg,png,gif|max:2048'
//            'file' => 'image'
        ]);

        return $validated;
    }

}