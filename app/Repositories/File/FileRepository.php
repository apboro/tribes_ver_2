<?php


namespace App\Repositories\File;


use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Auth;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\ImageManagerStatic as Image;
use function PHPUnit\Framework\isInstanceOf;

class FileRepository implements FileRepositoryContract
{
    public $imageTypes = [
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

    private $fields = [
        'mime',
        'size',
        'filename',
        'rank',
        'description',
        'isImage',
        'isVideo',
        'isAudio',
        'url',
        'hash',
        'uploader_id',
    ];

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
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
    /*private function setUrl($path)
    {
        return $this->url = '/storage/' . $path . $this->filename;
    }
    private function setFilename($file)
    {
        return $this->filename = $this->setHash($file) . '.' . $file->guessClientExtension();
    }
    private function prepareDirectory($type, $local = false)
    {
        $absolutPath = $type . '/' . Carbon::now()->format('d_m_y') . '/';
        $this->setUrl($absolutPath);
        $path = $local ? 'public/' . $absolutPath : storage_path('app/public/') . $absolutPath;

        if (!file_exists(storage_path('app/public/') . $absolutPath)) {
            mkdir(storage_path('app/public/') . $absolutPath, 0755, true);
        }
        return $path;
    }*/
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
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
    public function storeFile($data)
    {

        $file = $data['file'];

        $this->setDescription($data);
        $this->setUploader($file);
        $this->setMime($file);
        $this->setSize($file);

        $this->setRank($file);
        $this->setExtension($file);
        $this->setFilename($file);

        $isImage = $this->isImage($file);
        $isVideo = $this->isVideo($file);
        $isAudio = $this->isAudio($file);

        if($isImage){
            //$this->validateImage($file);

//            $image = Image::make($file)->encode('jpg', 75);

            $this->extension = 'jpg';
            $this->mime = 'image/jpg';
            $this->setFilename($file);
            if($data['crop']){
                $this->crop($data['cropData'], $file);
                $file = $this->formatImage($file);
                $this->setUrl($file);
                $path = $this->prepareDirectory('image') . $this->filename;
                $file->save($path);
            } else {
                $file = $this->formatImage($file);
                $this->setUrl($file);
                $path = $this->prepareDirectory('image') . $this->filename;
                $file->save($path);
            }
        } elseif($isVideo){
            $this->validateVideo($data);
            $path = $this->prepareDirectory('video', true);
            $file->storeAs($path, $this->filename);
        } else {
            $this->validateDocument($data);
        }
        $file = File::create($this->collectFileData()->only($this->fields)->toArray());

        return $file;
    }

    public function crop($cropdata, $image)
    {
        $dimensions = explode('|', $cropdata);
        $image->crop((int)$dimensions[2], (int)$dimensions[3], (int)$dimensions[0], (int)$dimensions[1]);
    }

    private function setDescription($data)
    {
        return $this->description = isset($data['description']) ? $data['description'] : 'File';
    }

    private function isImage($file)
    {
        return $this->isImage = in_array($file->getMimeType(), $this->imageTypes);
    }

    private function isVideo($file)
    {
        return $this->isVideo = in_array($file->getMimeType(), $this->videoTypes);
    }

    private function isAudio($file)
    {
        return $this->isAudio = in_array($file->getMimeType(), $this->audioTypes);
    }

    private function setMime($file)
    {
        return $this->mime = $file->getMimeType();
    }

    private function setUploader()
    {
        return env('APP_DEBUG') ?
            $this->uploader_id = User::where('email', 'test-dev@webstyle.top')->first()->id :
            $this->uploader_id = Auth::id();
    }

    private function setSize($file)
    {
        return $this->size = $file->getSize();
    }

    private function setRank($file)
    {
        return $this->rank = 0;
    }

    private function setUrl($path)
    {
        return $this->url = '/storage/' . $path . $this->filename;
    }

    public function setHash($file)
    {
        return md5($file . Carbon::now()) ;
    }

    private function setFilename(UploadedFile $file)
    {
        return $this->filename = $this->setHash($file) . '.' . $file->guessExtension();
    }

    private function collectFileData()
    {
        $data = [];
        foreach ($this->fields as $field){
            $data[$field] = $this->$field;
        }
        return collect($data);
    }

    private function validateImage($request)
    {
        if($request['crop']){
            $validated = $request->validate([
                'file' => 'required|mimes:jpg,png,gif|max:2048',
                'crop_data' => 'required_if:crop,true',
            ]);
        }
        return $validated;
    }

    private function validateVideo($request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:mp4,x-m4v|max:100480',
        ]);

        return $validated;
    }

    private function validateAudio($request)
    {
        $validated = request()->validate([
            'file' => 'required|mimes:mp3,wav,aac|max:100480',
        ]);

        return $validated;
    }


    private function validateDocument($request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);
        return $validated;
    }

    private function formatImage($image)
    {
        $image->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        return $image;
    }

    private function prepareDirectory($type, $local = false)
    {
        $absolutPath = $type . '/' . Carbon::now()->format('d_m_y') . '/';
        $this->setUrl($absolutPath);
        $path = $local ? 'public/' . $absolutPath : storage_path('app/public/') . $absolutPath;

        if (!file_exists(storage_path('app/public/') . $absolutPath)) {
            mkdir(storage_path('app/public/') . $absolutPath, 0755, true);
        }
        return $path;
    }

    private function setExtension($file)
    {
        return $this->extension = $file->guessClientExtension();
    }

    private function storeImage(StoreImageRequest $request)
    {
//        dd($request);
    }

    private function storeDocument(StoreDocumentRequest $request)
    {
//        dd(2);
    }
}
