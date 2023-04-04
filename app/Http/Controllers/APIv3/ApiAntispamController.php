<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiAntispamStoreRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Antispam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAntispamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ApiResponse
     */
    public function index(): ApiResponse
    {
        return ApiResponse::success();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ApiAntispamStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiAntispamStoreRequest $request): ApiResponse
    {
        $antispam = Antispam::create([
            'owner' => Auth::user()->id,
            'name' => $request->input('name'),
            'del_message_with_link' => $request->boolean('del_message_with_link'),
            'ban_user_contain_link' => $request->boolean('ban_user_contain_link'),
            'del_message_with_forward' => $request->boolean('del_message_with_forward'),
            'ban_user_contain_forward' => $request->boolean('ban_user_contain_forward'),
            'work_period' => $request->input('work_period')
        ]);
        if ($antispam === null) {
            return ApiResponse::error(trans('responses/common.antispam.add_error'));
        }
        return ApiResponse::success();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Antispam $antispam
     * @return \Illuminate\Http\Response
     */
    public function show(Antispam $antispam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Antispam $antispam
     * @return \Illuminate\Http\Response
     */
    public function edit(Antispam $antispam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Antispam $antispam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Antispam $antispam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Antispam $antispam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Antispam $antispam)
    {
        //
    }
}
