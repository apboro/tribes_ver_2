<?php

namespace App\Http\Controllers\APIv3\Webinar;

use App\Http\ApiRequests\Webinars\ApiFavouriteWebinarDeleteRequest;
use App\Http\ApiRequests\Webinars\ApiFavouriteWebinarStoreRequest;
use App\Http\ApiRequests\Webinars\ApiFavouriteWebinarListRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\FavouriteWebinar;
use App\Models\Webinar;
use Illuminate\Support\Facades\Auth;
use App\Http\ApiResources\Webinar\WebinarResource;

class ApiFavouriteWebinarController extends Controller
{

    /**
     * Добавляем вебинар в избранное
     */
    public function store(ApiFavouriteWebinarStoreRequest $request): ApiResponse
    {
        $user = Auth::user();
        $favorite = FavouriteWebinar::firstOrCreate([
            'user_id' => $user->id,
            'webinar_id' => $request->input('webinar_id')
        ]);

        return ApiResponse::success('common.added');
    }

    /**
     * Удаляем вебинар из избранного
     */
    public function destroy(ApiFavouriteWebinarDeleteRequest $request, int $id): ApiResponse
    {
        $user = Auth::user();
        FavouriteWebinar::where('user_id', $user->id)->where('webinar_id', $id)->delete();

        return ApiResponse::success('common.success');
    }

    /**
     * Список вебинаров в избранном
     * 
     * @todo TBS-1595
     */
    public function list(ApiFavouriteWebinarListRequest $request): ApiResponse
    {
        $user = Auth::user();
        $webinars = Webinar::select('webinars.id', 'uuid', 'external_id', 'external_url', 'background_image', 'author_id', 'title', 'description', 'start_at', 'end_at', 'webinar_id')
            ->with('favourites')->whereRelation('favourites', 'user_id', $user->id)
            ->leftJoin('favourite_webinars', 'favourite_webinars.webinar_id', '=', 'webinars.id')
            ->orderBy('favourite_webinars.created_at', 'DESC');

        $count = $webinars->count();
        
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ]
        )->items((WebinarResource::collection(
                $webinars->skip($request->offset ?? 0)->take($request->limit ?? 3)->get()
            ))->toArray($request));
    }
}
