<?php

namespace App\Http\Controllers\APIv3\Community\Rules;

use App\Http\ApiRequests\Antispam\ApiAntispamEditRequest;
use App\Http\ApiRequests\Antispam\ApiAntispamShowRequest;
use App\Http\ApiRequests\Antispam\ApiAntispamStoreRequest;
use App\Http\ApiRequests\ApiAntispamDeleteRequest;
use App\Http\ApiResources\Rules\ApiAntispamCollection;
use App\Http\ApiResources\Rules\ApiAntispamResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Antispam;
use App\Models\Community;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ApiAntispamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ApiResponse
     */
    public function list(): ApiResponse
    {
        $antispam = Antispam::where('owner', Auth::user()->id)->paginate(25);
        return ApiResponse::listPagination()->items(new ApiAntispamCollection($antispam));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ApiAntispamStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiAntispamStoreRequest $request): ApiResponse
    {
        /** @var Antispam $antispam */
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

            /** @var Community $communities */
            foreach ($request->input('community_ids') as $community_id) {
                $community = Community::where('owner', Auth::user()->id)->where('id', $community_id)->first();
                $community->antispam_uuid = $antispam->uuid;
                $community->save();
            }

        return ApiResponse::success('common.success');
    }

    /**
     * Display the specified resource.
     *
     * @param ApiAntispamShowRequest $request
     * @param $uuid
     * @return ApiResponse
     */
    public function show(ApiAntispamShowRequest $request, $uuid): ApiResponse
    {
        $antispam = Antispam::where('owner', Auth::user()->id)->where('uuid', $uuid)->first();

        if ($antispam === null) {
            return ApiResponse::notFound(trans('responses/common.not_found'));
        }
        return ApiResponse::list()->items(ApiAntispamResource::make($antispam)->toArray($request));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ApiAntispamEditRequest $request
     * @param int $id
     * @return Response
     */
    public function edit(ApiAntispamEditRequest $request, string $uuid): ApiResponse
    {
        $antispam = Antispam::where('owner', Auth::user()->id)->where('uuid', $uuid)->first();

        if ($antispam === null) {
            return ApiResponse::notFound(trans('responses/common.not_found'));
        }
        $antispam->fill([
            'owner' => Auth::user()->id,
            'name' => $request->input('name'),
            'del_message_with_link' => $request->boolean('del_message_with_link'),
            'ban_user_contain_link' => $request->boolean('ban_user_contain_link'),
            'del_message_with_forward' => $request->boolean('del_message_with_forward'),
            'ban_user_contain_forward' => $request->boolean('ban_user_contain_forward'),
            'work_period' => $request->input('work_period')
        ]);
        $antispam->save();

        if ($request->has('community_ids')) {
            Community::where('owner', Auth::user()->id)
                ->where('antispam_uuid', $uuid)
                ->update(['antispam_uuid' => null]);
            if (!empty($request->input('community_ids'))) {
                /** @var Community $community */
                foreach ($request->input('community_ids') as $row) {
                    $community = Community::where('owner', Auth::user()->id)->where('id', $row)->first();
                    $community->antispam_uuid = $antispam->uuid;
                    $community->save();
                }
            }
        }
        return ApiResponse::success('common.success');
    }

    public function delete(ApiAntispamDeleteRequest $request)
    {
        $antispam_rule = Antispam::where('owner', Auth::user()->id)->where('uuid', $request->antispam_uuid)->first();
        if ($antispam_rule){
            $antispam_rule->delete();
            return ApiResponse::success('common.deleted');
        }
        return ApiResponse::error('common.not_found');
    }
}
