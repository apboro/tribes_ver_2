<?php

namespace App\Repositories\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Auth;
use Illuminate\Support\Facades\File as FileFacade;

class FileRepository implements FileRepositoryContract
{

    public function get($id)
    {
        $file = File::find($id);

        if(!$file) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не найдено',
                'details' => 'Файл не найден или у вас нет прав для редактирования',
            ]);
        } else {
            return response()->json([
                "status" => "ok",
                "details" => "",
                'file' => $file
            ]);
        }
    }

    public function delete($id)
    {
        $file = File::find($id);
        if(!$file->isVideo){
            unlink(storage_path('app/public/' . str_replace('/storage/', '', $file->url)));
        }

        $file->delete();

        return response()->json([
            "status" => "ok",
            "details" => "Файл успешно удален"
        ]);
    }

    public function storeFileNew(UploadedFile $file, $path, $filename)
    {
        $absolutPath = $path . '/' . Carbon::now()->format('d_m_y');

        if (!file_exists(storage_path('app/public/') . $absolutPath)) {
            mkdir(storage_path('app/public/') . $absolutPath, 0755, true);
        }

        $file->storeAs('public/' . $absolutPath, $filename, ['disk' => 'local']);

        return '/storage/' . $absolutPath . '/' . $filename;
    }

    public function saveImageForSeeder($path)
    {
        $file = $this->pathToUploadedFile($path);

        $model = new File([
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'filename' => $this->setHash($file) . '.' . $file->guessClientExtension(),
            'rank' => 0,
            'isImage' => 1,
            'url' => $this->storeFileNew($file, 'image', $this->setHash($file) . '.' . $file->guessClientExtension()),
            'hash' => $this->setHash($file),
            'uploader_id' => $this->setUploader(),
            'isVideo' => 0,
            'isAudio' => 0,
            'remoteFrame' => null,
            'webcaster_event_id' => null,
            'description' => null,
            'iframe' => null
        ]);
        $model->save();

        return $model;
    }

    public static function pathToUploadedFile( $path, $public = false )
    {
        $name = FileFacade::name( $path );

        $extension = FileFacade::extension( $path );

        $originalName = $name . '.' . $extension;

        $mimeType = FileFacade::mimeType( $path );

        $size = FileFacade::size( $path );

        $error = null;

        $test = $public;

        $object = new UploadedFile($path, $originalName, $mimeType, $error, false);

        return $object;
    }

    private function setUploader()
    {
        return env('APP_DEBUG') ?
            $this->uploader_id = User::where('email', 'test-dev@webstyle.top')->first()->id :
            $this->uploader_id = Auth::id();
    }

    public function setHash($file)
    {
        return md5(basename($file) . Carbon::now() . rand(1,9999)) ;
    }
}
