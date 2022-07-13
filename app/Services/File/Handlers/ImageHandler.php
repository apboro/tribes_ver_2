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

    private $fields = [
        'url',
        'hash',
        'filename'
    ];

    public function __construct(string $path, FileRepository $repository)
    {

//        $this->path = Storage::disk('public')->path($path);
        $this->path = $path;
        $this->repository = $repository;
    }


    public function startService(UploadedFile $file, File $model): File
    {

//dd($this->path);
//        dd($model, $file);
        //как-то обрабатываем картинку (crop, resize и т.д.)
        //todo
        //репозиторий делает сохранение файла в нужную папку и возвращает полное имя файла $file->storeAs($this->path.$file->getClientOriginalName());

//        $model->file;




        $this->repository->storeFileTest($file,  $this->path);
//dd($file);
//dd($file->getClientOriginalName());
//        $new_file = $file->storeAs($this->path, $file->getClientOriginalName());
//        dd($file);
        $url = ;
        $hash = ;
        $filename = ;


        $model['mime'] = $file->getMimeType();
        $model['size'] = $file->getSize();
        $model['isImage'] = 1;
        $model['filename'] = 1;
        $model['url'] = 1;
        $model['hash'] = 1;

        $model->save();
//        dd($file);
        return $model;
    }

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
        return $this->filename = $this->setHash($file) . '.' . $file->guessClientExtension();
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