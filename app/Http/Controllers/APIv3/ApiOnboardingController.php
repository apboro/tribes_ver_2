<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiDeleteOnboardingRequest;
use App\Http\ApiRequests\ApiGetOnboardingRequest;
use App\Http\ApiRequests\ApiStoreOnboardingRequest;
use App\Http\ApiRequests\ApiUpdateOnboardingRequest;
use App\Http\ApiResources\ApiOnboardingsCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Onboarding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiOnboardingController extends Controller
{
    public function store(ApiStoreOnboardingRequest $request): ApiResponse
    {
        $user_id = Auth::user()->id;

        $greetingImagePath = $request->file('greeting_image') ? Storage::disk('public')->putFile('greeting_images', $request->file('greeting_image')) : null;
        $questionImagePath = $request->file('question_image') ? Storage::disk('public')->putFile('question_images', $request->file('question_image')) : null;

        $onboarding = new Onboarding();
        $onboarding->rules = $request->input('rules');
        $onboarding->user_id = $user_id;
        $onboarding->title = $request->input('title');
        $onboarding->greeting_image = $greetingImagePath;
        $onboarding->question_image = $questionImagePath;
        $onboarding->save();

        foreach ($request->input('communities_ids') as $community_id) {
            $onboarding->communities()->attach($community_id);
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
        $greetingImagePath = $request->file('greeting_image') ? Storage::disk('public')->putFile('greeting_images', $request->file('greeting_image')) : null;
        $questionImagePath = $request->file('question_image') ? Storage::disk('public')->putFile('question_images', $request->file('question_image')) : null;

        $onboarding = Onboarding::find($request->onboarding_id);
        $onboarding->rules = $request->input('rules');
        $onboarding->title = $request->input('title');
        $onboarding->greeting_image = $greetingImagePath;
        $onboarding->question_image = $questionImagePath;
        $onboarding->save();

        foreach ($request->input('communities_ids') as $community_id) {
            $onboarding->communities()->attach($community_id);
        }

        return ApiResponse::success('common.updated');
    }

    public function destroy(ApiDeleteOnboardingRequest $request)
    {
        $ruleToDelete = Onboarding::findOrFail($request->onboarding_id);
        $ruleToDelete->delete();
        return ApiResponse::success('common.deleted');
    }


}
