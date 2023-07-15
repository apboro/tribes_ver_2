<?php

namespace App\Repositories\Publication;


use App\Http\ApiRequests\Publication\ApiPublicationPartStoreRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPartUpdateRequest;
use App\Models\PublicationPart;
use Illuminate\Support\Facades\Storage;

class PublicationPartRepository
{
    const MEDIA_TYPE_TEXT = 1;
    const MEDIA_TYPE_VIDEO = 2;
    const MEDIA_TYPE_AUDIO = 3;
    const MEDIA_TYPE_IMAGE = 4;
    const MEDIA_TYPE_OTHER = 5;
    const MEDIA_TYPE_HEADER = 6;

    public function store(ApiPublicationPartStoreRequest $request)
    {
        $file_path = null;
        if ($request->input('type') != $this::MEDIA_TYPE_TEXT) {
            $file_path = $request->file('file') ? Storage::disk('public')->putFile('publication_images', $request->file('file')) : null;
        }
        $publication_part = PublicationPart::create([
            'publication_id' => $request->input('publication_id'),
            'type' => $request->input('type'),
            'file_path' => $file_path,
            'text' => $request->input('text'),
            'order' => $request->input('order')
        ]);
        return $publication_part;
    }


    public function update(ApiPublicationPartUpdateRequest $request, int $id)
    {
        $file_path = null;
        if ($request->input('type') != $this::MEDIA_TYPE_TEXT) {
            $file_path = $request->file('file') ? Storage::disk('public')->putFile('publication_images', $request->file('file')) : null;
        }
        $publication_part = PublicationPart::find($id);
        $array_to_fill = [
            'type' => $request->input('type'),
            'text' => $request->input('text'),
            'order' => $request->input('order')
        ];
        if ($request->exists('file')) {
            $array_to_fill['file_path'] = $file_path;
        }
        $publication_part->fill($array_to_fill);
        $publication_part->save();
        return $publication_part;
    }
}