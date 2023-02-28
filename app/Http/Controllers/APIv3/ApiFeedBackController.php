<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiFeedBackRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class ApiFeedBackController extends Controller
{
    public function store(ApiFeedBackRequest $request):ApiResponse
    {
        $feed_back = Feedback::create([
            'user_id' => Auth::user()->id,
            'email' => $request->input('fb_email'),
            'phone' => $request->input('fb_phone') ?? null,
            'name' => $request->input('fb_name') ?? null,
            'text' => $request->input('fb_message'),
            'status' => 'Новый',
        ]);
        if(empty($feed_back)){
            return ApiResponse::error('common.feed_back_error');
        }
        return ApiResponse::common(['feed_back'=>$feed_back]);
    }
}
