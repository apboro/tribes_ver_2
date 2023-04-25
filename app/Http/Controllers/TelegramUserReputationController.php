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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTelegramUserReputationRequest  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TelegramUserReputation  $telegramUserReputation
     * @return \Illuminate\Http\Response
     */
    public function show(TelegramUserReputation $telegramUserReputation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TelegramUserReputation  $telegramUserReputation
     * @return \Illuminate\Http\Response
     */
    public function edit(TelegramUserReputation $telegramUserReputation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTelegramUserReputationRequest  $request
     * @param  \App\Models\TelegramUserReputation  $telegramUserReputation
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TelegramUserReputation  $telegramUserReputation
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelegramUserReputation $telegramUserReputation)
    {
        //
    }
}
