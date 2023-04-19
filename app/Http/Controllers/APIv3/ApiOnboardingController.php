<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiStoreOnboardingRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\GreetingMessage;
use App\Models\Onboarding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiOnboardingController extends Controller
{
    public function store(ApiStoreOnboardingRequest $request): ApiResponse
    {
        $user_id = Auth::user()->id;

        $path = Storage::disk('public')->putFile('greeting_images', $request->file('image'));
        $message = new GreetingMessage();
        $message->text = $request->input('greeting_message_text');
        $message->image = $path;
        $message->user_id = $user_id;
        $message->save();

        $onboarding = new Onboarding();
        $onboarding->rules = $request->input('rules');
        $onboarding->user_id = $user_id;
        $onboarding->title = $request->input('title');
        $onboarding->greeting_message_id = $message->id;
        $onboarding->save();

        foreach ($request->input('communities_ids') as $community_id) {
            $onboarding->communities()->attach($community_id);
        }

        return ApiResponse::success('Правила сохранены');
    }
}
