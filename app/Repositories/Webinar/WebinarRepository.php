<?php

namespace App\Repositories\Webinar;

use App\Http\ApiRequests\Webinars\ApiWebinarsStoreRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsUpdateRequest;
use App\Models\User;
use App\Models\Webinar;
use App\Services\WebinarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WebinarRepository
{
    private WebinarService $webinarService;

    public function __construct(WebinarService $webinarService)
    {
        $this->webinarService = $webinarService;
    }

    public function add(ApiWebinarsStoreRequest $request)
    {
        /**@var User $user */
        $user = Auth::user();
        $api_add_array = [
            'name' => $request->input('title'),
            'desc' => $request->input('description'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
        ];
        $add_result = $this->webinarService->add($api_add_array);
        if ($add_result === false) {
            return null;
        }
       // var_dump($add_result);
       // die();
        $background_image = $request->file('background_image') ? Storage::disk('public')->putFile('webinar_image', $request->file('background_image')) : null;

        return Webinar::create([
            'author_id' => $user->author->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'external_id' => $add_result->id,
            'external_url' => $add_result->url,
            'background_image' => $background_image,
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
        ]);
    }

    public function delete(int $id): bool
    {
        /**@var User $user */
        $user = Auth::user();
        $webinar = Webinar::where('author_id', $user->author->id)->where('id', $id)->first();
        if ($webinar === null) {
            return false;
        }
        $delete_result = $this->webinarService->delete(['id' => $webinar->external_id]);
        if (!$delete_result) {
            return false;
        }
        $webinar->delete();
        return true;
    }

    public function update(ApiWebinarsUpdateRequest $request, int $id)
    {
        /**@var User $user */
        $user = Auth::user();
        $webinar = Webinar::where('author_id', $user->author->id)->where('id', $id)->first();
        if ($webinar === null) {
            return null;
        }
        $api_update_array = [
            'id' => $webinar->external_id,
            'name' => $request->input('title'),
            'desc' => $request->input('description'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
        ];
        $update_result = $this->webinarService->update($api_update_array);
        if ($update_result === false) {
            return null;
        }
        $background_image = $request->file('background_image') ? Storage::disk('public')->putFile('webinar_image', $request->file('background_image')) : null;

        $fill_array = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'external_url' => $update_result->url,
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
        ];

        if ($request->exists('background_image')) {
            $fill_array = array_merge($fill_array, ['background_image' => $background_image]);
        }

        $webinar->fill($fill_array);
        $webinar->save();
        return $webinar;
    }

    public function show(int $id)
    {
        /**@var User $user */
        $user = Auth::user();
        $webinar = Webinar::where('author_id', $user->author->id)->where('id', $id)->first();
        if ($webinar === null) {
            return null;
        }
        return $webinar;
    }

    public function list()
    {
        /**@var User $user */
        $user = Auth::user();
        $webinars = Webinar::where('author_id', $user->author->id);
        return $webinars;
    }

}