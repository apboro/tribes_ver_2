<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\Author\ApiAuthorDelete;
use App\Http\ApiRequests\Author\ApiAuthorShowForFollowersRequest;
use App\Http\ApiRequests\Author\ApiAuthorShowRequest;
use App\Http\ApiRequests\Author\ApiAuthorsShowListRequest;
use App\Http\ApiRequests\Author\ApiAuthorStoreRequest;
use App\Http\ApiRequests\Author\ApiAuthorUpdateRequest;
use App\Http\ApiResources\AuthorResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiAuthorController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiAuthorStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiAuthorStoreRequest $request): ApiResponse
    {
        /**
         * @var User $user
         */

        $user = Auth::user();

        $photo = $request->file('photo') ? Storage::disk('public')->putFile('author_photo', $request->file('photo')) : null;

        $author = Author::where('user_id', $user->id)->first();

        if ($author === null) {
            $author = Author::create([
                'user_id' => $user->id,
                'name' => $request->input('name') ?? $user->name,
                'about' => $request->input('about') ?? null,
                'photo' => $photo
            ]);
        } else {
            $this->updateEntity($author, $request, $user, $photo);
        }

        if ($author === null) {
            return ApiResponse::error('common.add_error');
        }

        return ApiResponse::common(AuthorResourse::make($author)->toArray($request));
    }

    public function list(ApiAuthorsShowListRequest $request)
    {
        $authorList = Author::all();

        return ApiResponse::common(AuthorResourse::make($authorList)->toArray($request));
    }

    /**
     * Display the specified resource.
     *
     * @param ApiAuthorShowRequest $request
     * @return ApiResponse
     */
    public function show(ApiAuthorShowRequest $request, $id): ApiResponse
    {
        $author = Author::where('id', $id)->first();
        if ($author === null) {
            return ApiResponse::notFound('common.not_found');
        }
        return ApiResponse::common(AuthorResourse::make($author)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiAuthorUpdateRequest $request
     * @param Author $author
     * @return ApiResponse
     */
    public function update(ApiAuthorUpdateRequest $request): ApiResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $photo = $request->file('photo') ? Storage::disk('public')->putFile('author_photo', $request->file('photo')) : null;
        $author = Author::where('user_id', $user->id)->first();

        if ($author === null) {
            return ApiResponse::notFound('common.not_found');
        }
        $this->updateEntity($author, $request, $user, $photo);

        return ApiResponse::common(AuthorResourse::make($author)->toArray($request));
    }

    private function updateEntity(Author $author, Request $request, User $user, $photo)
    {
        $fill_array = [
            'name' => $request->input('name') ?? null,
            'about' => $request->input('about') ?? null,
        ];
        if ($request->exists('photo')) {
            $fill_array = array_merge($fill_array, ['photo' => $photo]);
        }


        $author = $author->fill($fill_array);

        $author->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Author $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApiAuthorDelete $request): ApiResponse
    {
        $user = Auth::user();
        $author = Author::where('user_id', $user->id)->first();
        if ($author === null) {
            return ApiResponse::notFound('common.not_found');
        }
        Author::where('user_id', $user->id)->delete();
        return ApiResponse::success();
    }

    public function showForFollowers(ApiAuthorShowForFollowersRequest $request, int $id)
    {
        $author = Author::find($id);
        return ApiResponse::common(AuthorResourse::make($author)->toArray($request));
    }

}
