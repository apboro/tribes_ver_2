<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiFavouritePublicationDeleteRequest;
use App\Http\ApiRequests\Publication\ApiFavouritePublicationListRequest;
use App\Http\ApiRequests\Publication\ApiFavouritePublicationStoreRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\FavouritePublication;
use Illuminate\Support\Facades\Auth;

class ApiFavouritePublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ApiFavouritePublicationListRequest $request
     * @return ApiResponse
     */

    public function list(ApiFavouritePublicationListRequest $request): ApiResponse
    {

        //   return ApiResponse::common();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiFavouritePublicationStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiFavouritePublicationStoreRequest $request): ApiResponse
    {
        $user = Auth::user();
        $favorite = FavouritePublication::firstOrCreate([
            'user_id' => $user->id,
            'publication_id' => $request->input('publication_id')
        ]);
        if ($favorite === null) {
            return ApiResponse::error('common.add_error');
        }
        return ApiResponse::success('common.added');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApiFavouritePublicationDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiFavouritePublicationDeleteRequest $request, int $id): ApiResponse
    {
        $user = Auth::user();
        FavouritePublication::where('user_id', $user->id)->where('publication_id', $id)->delete();
        return ApiResponse::success('common.success');
    }
}
