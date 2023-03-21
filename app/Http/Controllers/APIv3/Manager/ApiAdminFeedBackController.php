<?php

namespace App\Http\Controllers\APIv3\Manager;

use App\Events\FeedBackAnswer;
use App\Http\ApiRequests\Admin\ApiManagerFeedBackAnswerRequest;
use App\Http\ApiRequests\Admin\ApiManagerFeedBackCloseRequest;
use App\Http\ApiRequests\Admin\ApiManagerFeedBackListRequest;
use App\Http\ApiRequests\Admin\ApiManagerFeedBackShowRequest;
use App\Http\ApiResources\FeedBackCollection;
use App\Http\ApiResources\FeedBackResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\FeedbackFilter;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class ApiAdminFeedBackController extends Controller
{
    /**
     * @param ApiManagerFeedBackAnswerRequest $request
     * @return ApiResponse
     */
    public function answer(ApiManagerFeedBackAnswerRequest $request): ApiResponse
    {
        /** @var User $user */
        $manager = Auth::user();

        $answer = $request->input('message');
        /** @var Feedback $feedback */
        $feedback = Feedback::where('id', $request->input('id'))->update([
            'answer' => $answer,
            'manager_user_id' => $manager->id,
            'status' => 'Отвечен'
        ]);

        if ($feedback === null) {
            return ApiResponse::error('common.admin.feed_back_update_error');
        }
        $feedback = Feedback::where('id', '=', $request->input('id'))->first();

        /** @var User $user */
        $user = User::where('id', '=', $feedback->user_id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }
        Event::dispatch(new FeedBackAnswer($user, $answer));

        return ApiResponse::success('common.admin.feed_back_answer_send');
    }

    /**
     * @param ApiManagerFeedBackCloseRequest $request
     * @param int $id
     * @return ApiResponse
     */

    public function close(ApiManagerFeedBackCloseRequest $request, int $id): ApiResponse
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::where('id', '=', $id)->first();
        $feedback->status = 'Закрыт';
        if (!$feedback->save()) {
            return ApiResponse::error('common.admin.feed_back_update_error');
        }

        return ApiResponse::success('common.admin.feed_back_close_success');
    }

    /**
     * @param ApiManagerFeedBackShowRequest $request
     * @param int $id
     * @return ApiResponse
     */

    public function show(ApiManagerFeedBackShowRequest $request, int $id): ApiResponse
    {
        /** @var Feedback $feedback */

        $feedback = Feedback::where('id', '=', $id)->first();
        return ApiResponse::common(FeedBackResource::make($feedback)->toArray($request));
    }

    /**
     * @param ApiManagerFeedBackListRequest $request
     * @param FeedbackFilter $filter
     * @return ApiResponse
     */

    public function list(ApiManagerFeedBackListRequest $request, FeedbackFilter $filter): ApiResponse
    {

        /** @var Feedback $feedbacks */
        $feedbacks = Feedback::query()->
                    filter($filter)->
                    paginate(10);

        return ApiResponse::list()->items(FeedBackCollection::make($feedbacks)->toArray($request));
    }

}
