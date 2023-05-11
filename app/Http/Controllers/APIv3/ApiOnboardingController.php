<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiDeleteOnboardingRequest;
use App\Http\ApiRequests\ApiGetOnboardingRequest;
use App\Http\ApiRequests\ApiShowOnboardingRequest;
use App\Http\ApiRequests\ApiStoreOnboardingRequest;
use App\Http\ApiRequests\ApiUpdateOnboardingRequest;
use App\Http\ApiResources\Rules\ApiOnboardingsCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Onboarding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiOnboardingController extends Controller
{
    public function store(ApiStoreOnboardingRequest $request): ApiResponse
    {
        $user_id = Auth::user()->id;

        $greetingImagePath = $request->file('greeting_image') ? Storage::disk('public')->putFile('storage/greeting_images', $request->file('greeting_image')) : null;
        $questionImagePath = $request->file('question_image') ? Storage::disk('public')->putFile('storage/question_images', $request->file('question_image')) : null;

        $onboarding = new Onboarding();
        $onboarding->rules = $request->input('rules');
        $onboarding->user_id = $user_id;
        $onboarding->title = $request->input('title');
        $onboarding->greeting_image = $greetingImagePath;
        $onboarding->question_image = $questionImagePath;
        $onboarding->save();
        foreach ($request->input('communities_ids') as $community_id) {
            $community = Community::where('id', $community_id)->where('owner', Auth::user()->id)->first();
            if ($community !== null) {
                $community->onboarding_uuid = $onboarding->uuid;
                $community->save();
            }
        }

        return ApiResponse::success('common.added');
    }

    public function get(ApiGetOnboardingRequest $request): ApiResponse
    {
        $onboardings = Onboarding::where('user_id', Auth::user()->id)->get();

        return ApiResponse::list()->items(ApiOnboardingsCollection::make($onboardings)->toArray($request));
    }

    public function update(ApiUpdateOnboardingRequest $request): ApiResponse
    {
        $greetingImagePath = $request->file('greeting_image') ? Storage::disk('public')->putFile('storage/greeting_images', $request->file('greeting_image')) : null;
        $questionImagePath = $request->file('question_image') ? Storage::disk('public')->putFile('storage/question_images', $request->file('question_image')) : null;

        $onboarding = Onboarding::find($request->onboarding_uuid);
        $onboarding->title = $request->input('title');
        $onboarding->rules = $request->input('rules');
        $onboarding->question_image = $questionImagePath;
        $onboarding->greeting_image = $greetingImagePath;
        $onboarding->save();

        foreach ($request->input('communities_ids') as $community_id) {
            $community = Community::where('id', $community_id)->where('owner', Auth::user()->id)->first();
            if ($community !== null) {
                $community->onboarding_uuid = $onboarding->uuid;
                $community->save();
            }
        }

        return ApiResponse::success('common.updated');
    }

    public function destroy(ApiDeleteOnboardingRequest $request)
    {
        $ruleToDelete = Onboarding::findOrFail($request->onboarding_uuid);
        $ruleToDelete->delete();
        return ApiResponse::success('common.deleted');
    }

    public function show(ApiShowOnboardingRequest $request): ApiResponse
    {
        $onboarding = Onboarding::where('user_id', Auth::user()->id)
            ->where('uuid', $request->onboarding_uuid)->first();

        return ApiResponse::common($onboarding);
    }


}
