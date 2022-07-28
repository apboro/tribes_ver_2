<?php /** @noinspection PhpHierarchyChecksInspection */

namespace App\Services\File\Handlers;


use App\Models\File;
use App\Repositories\File\FileRepository;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        dd($file);
        foreach ($procedure as $key => $proc) {
//            dd($key);
            switch ($key) {
                case 'crop':
                    $this->crop($proc, $file);
                    break;
            }

//            $this->proc . 'Function' . ();
        }
        //как-то обрабатываем картинку (crop, resize и т.д.)
        //todo
        //репозиторий делает сохранение файла в нужную папку и возвращает полное имя файла $file->storeAs($this->path.$file->getClientOriginalName());


        $hash = $this->repository->setHash($file);
        $filename = $hash . '.' . $file->guessClientExtension();
//        dd($filename);
//        $url = $this->repository;

        $url = $this->repository->storeFileNew($file, $this->path, $filename);

        $model['mime'] = $file->getMimeType();
        $model['size'] = $file->getSize();
        $model['isImage'] = 1;
        $model['filename'] = $filename;
        $model['url'] = $url;
        $model['hash'] = $hash;

        $model->save();
//        dd($file);
        return $model;
    }


    ////////////////////////////////////////////////////////

    public function crop($crop, $image)
    {
        $dimensions = explode('|', $crop);
        $image->crop((int)$dimensions[2], (int)$dimensions[3], (int)$dimensions[0], (int)$dimensions[1]);
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