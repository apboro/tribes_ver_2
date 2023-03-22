<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiCommunityTelegramUserDeleteRequest;
use App\Http\ApiRequests\ApiCommunityTelegramUserDetachAllRequest;
use App\Http\ApiRequests\ApiCommunityTelegramUserDetachRequest;
use App\Http\ApiRequests\ApiCommunityTelegramUserListRequest;
use App\Http\ApiRequests\ApiTelegramUserFilterRequest;
use App\Http\ApiResources\ApiCommunityTelegramUserCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ApiCommunityTelegramUserController extends Controller
{
    public function index(ApiCommunityTelegramUserListRequest $request):ApiResponse
    {
        /** @var LengthAwarePaginator $telegram_users */
        $telegram_users = TelegramUser::with(['communities'])
            ->whereHas('communities', function($query) { $query->where('owner', Auth::user()->id); })
            ->paginate(25);
        return ApiResponse::listPagination()->items(new ApiCommunityTelegramUserCollection($telegram_users));
    }


    /**
     * @param ApiCommunityTelegramUserDetachRequest $request
     * @return ApiResponse
     */
    public function detachUser(ApiCommunityTelegramUserDetachRequest $request):ApiResponse
    {
        /** @var TelegramUser $telegram_user */
        $telegram_user = TelegramUser::where('telegram_id','=',$request->input('telegram_id'))->first();

        /** @var Community $community */
        $community = Community::where('id','=',$request->input('community_id'))->first();

        $telegram_user->communities()->detach($community);
        return ApiResponse::success();
    }

    public function detachFromAllCommunities(ApiCommunityTelegramUserDetachAllRequest $request):ApiResponse
    {
        /** @var TelegramUser $telegram_user */
        $telegram_user = TelegramUser::where('telegram_id','=',$request->input('telegram_id'))->first();

        /** @var Community $community */
        $communities = Community::where('id', Auth::user()->id)->get();
        foreach ($communities as $community)
        {
            $telegram_user->communities()->detach($community);
        }
        return ApiResponse::success();
    }


    /**
     * @param ApiTelegramUserFilterRequest $request
     * @return ApiResponse
     */

    public function filter(ApiTelegramUserFilterRequest $request):ApiResponse
    {

        $query = TelegramUser::select('telegram_users.*')->
            addSelect('communities.*')->
            addSelect('telegram_users_community.accession_date')
            ->leftJoin(
                'telegram_users_community',
                'telegram_users_community.telegram_user_id',
                '=',
                'telegram_users.telegram_id')
            ->leftJoin(
                'communities',
                'communities.id',
                '=',
                'telegram_users_community.community_id'
            )
            ->whereHas('communities', function($query) { $query->where('owner', Auth::user()->id); })
            ->with(['communities'])->newQuery();
        if(!empty($request->input('accession_date_from'))){
            $query->whereHas('communities',function($query) use ($request){
                $query->where('telegram_users_community.accession_date','>=',strtotime($request->input('accession_date_from')));

            });
        }

        if(!empty($request->input('accession_date_to'))){
            $query->whereHas('communities',function($query) use ($request){
                $query->where('telegram_users_community.accession_date','<=',strtotime($request->input('accession_date_to')));

            });
        }

        if(!empty($request->input('community_id'))){
            $query->whereHas('communities',function($query) use ($request){
                $query->where('telegram_users_community.community_id','=',$request->input('community_id'));

            });
        }

        if(!empty($request->input('name'))){
            $query->where(function($query) use ($request){
                $query->where('first_name','ilike','%'.$request->input('name').'%')
                      ->orWhere('last_name','ilike','%'.$request->input('name').'%')
                      ->orWhere('user_name','ilike','%'.$request->input('name').'%');
            });
        }

        $telegram_users = $query->paginate(20);

        return ApiResponse::listPagination()->items(new ApiCommunityTelegramUserCollection($telegram_users));

    }
}
