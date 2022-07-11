<?php /** @noinspection PhpHierarchyChecksInspection */

namespace App\Services\File\Handlers;


use App\Models\File;
use App\Repositories\File\FileRepository;
use App\Services\File\common\HandlerContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHandler implements HandlerContract
{

    private $path;
    private FileRepository $repository;

    public function __construct(string $path, FileRepository $repository)
    {

        $this->path = Storage::disk('public')->path($path);
        $this->repository = $repository;
    }


    public function startService(UploadedFile $file, File $model): File
    {
        //todo
        //репозиторий делает сохранение файла в нужную папку и возвращает полное имя файла $file->storeAs($this->path.$file->getClientOriginalName());
        $this->validateImage();
        $model->file;
        $model->save();
//        dd($file);
        return $file;
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