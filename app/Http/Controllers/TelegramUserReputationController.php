<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\ApiGetTelegramUsersReputationRequest;
use App\Http\ApiResources\TelegramUserReputationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Models\TelegramUserReputation;
use Illuminate\Support\Facades\Auth;

class TelegramUserReputationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiGetTelegramUsersReputationRequest $request): ApiResponse
    {
        $resources = TelegramUserReputation::whereHas('community', function($community){
            $community->where('owner', Auth::user()->id);
        })->skip($request->offset)->take($request->limit)->orderBy('id')->get();
        return ApiResponse::listPagination(['Access-Control-Expose-Headers'=>'Items-Count', 'Items-Count'=>$resources->count()])->items(TelegramUserReputationResource::collection($resources));
    }


}
