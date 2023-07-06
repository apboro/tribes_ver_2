<?php

namespace App\Repositories\Publication;

use App\Http\ApiRequests\Publication\ApiPublicationStoreRequest;
use App\Http\ApiRequests\Publication\ApiPublicationUpdateRequest;
use App\Models\Author;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicationRepository
{

    public function store(ApiPublicationStoreRequest $request)
    {
        $background_image = $request->file('background_image') ? Storage::disk('public')->putFile('publication_images', $request->file('background_image')) : null;

        $author = Author::firstOrCreate(['user_id' => Auth::user()->id]);

        $create_array = [
            'author_id' => $author->id,
            'is_active' => $request->boolean('is_active'),
            'description' => $request->input('description'),
            'title' => $request->input('title'),
            'price' => $request->input('price')
        ];

        if ($request->exists('background_image')) {
            $create_array = array_merge($create_array, ['background_image' => $background_image]);
        }

        return Publication::create($create_array);
    }

    public function update(ApiPublicationUpdateRequest $request, int $id)
    {
        /** @var User $user */
        $user = Auth::user();
        $publication = Publication::where('id', $id)->where('author_id', $user->author->id)->first();
        if ($publication === null) {
            return null;
        }
        $background_image = $request->file('background_image') ? Storage::disk('public')->putFile('publication_images', $request->file('background_image')) : null;
        $fill_array = [
            'is_active' => $request->boolean('is_active'),
            'description' => $request->input('description'),
            'title' => $request->input('title'),
            'price' => $request->input('price')
        ];

        if ($request->exists('background_image')) {
            $fill_array = array_merge($fill_array, ['background_image' => $background_image]);
        }

        $publication->fill($fill_array);
        $publication->save();
        return $publication;
    }

}