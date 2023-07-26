<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\Publication\LMSFeedbackRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\LMSFeedbackResource;
use App\Models\LMSFeedback;
use App\Models\Publication;
use App\Models\Webinar;
use Illuminate\Support\Facades\Auth;

class ApiLMSFeedbackController extends Controller
{
    public function index()
    {
        return LMSFeedbackResource::collection(LMSFeedback::all());
    }

    public function store(LMSFeedbackRequest $request)
    {
        $publication = $request->type === 'webinar' ? Webinar::findOrFail($request->id) : Publication::findOrFail($request->id);
        $data = $request->all();
        $data['author_id'] = $publication->author->id;
        $data['user_id'] = Auth::user()->id;
        if ($request->type === 'webinar') {
            $data['webinar_id'] = $publication->id;
        } else {
            $data['publication_id'] = $publication->id;
        }
        LMSFeedback::create($data);
        return ApiResponse::success(trans('common.success'));
    }

    public function show(LMSFeedback $LMSFeedback)
    {
        return new LMSFeedbackResource($LMSFeedback);
    }

    public function update(LMSFeedbackRequest $request, LMSFeedback $LMSFeedback)
    {
        $LMSFeedback->update($request->validated());

        return new LMSFeedbackResource($LMSFeedback);
    }

    public function destroy(LMSFeedback $LMSFeedback)
    {
        $LMSFeedback->delete();

        return response()->json();
    }
}
