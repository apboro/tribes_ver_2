<?php

namespace App\Http\Controllers\APIv3;

use App\Events\FeedBackCreate;
use App\Http\ApiRequests\Profile\ApiFeedBackRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class ApiFeedBackController extends Controller
{
    /**
     * Add feedback from user.
     *
     * @param ApiFeedBackRequest $request
     *
     * @return ApiResponse
     */
    public function store(ApiFeedBackRequest $request): ApiResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $feed_back = Feedback::create([
            'user_id' => $user->id,
            'email' => $request->input('fb_email'),
            'phone' => $request->input('fb_phone'),
            'name' => $request->input('fb_name'),
            'text' => $request->input('fb_message'),
            'status' => 'Новый',
        ]);

        if ($feed_back === null) {
            return ApiResponse::error('common.feed_back_error');
        }

        Event::dispatch(new FeedBackCreate($user, $feed_back));

        return ApiResponse::common(['feed_back'=>$feed_back]);
    }
}
