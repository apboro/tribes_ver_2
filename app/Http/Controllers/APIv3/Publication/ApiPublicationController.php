<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Events\ApiUserRegister;
use App\Events\BuyCourse;
use App\Events\BuyPublicaionEvent;
use App\Http\ApiRequests\ApiCheckPostFeedbackRequest;
use App\Http\ApiRequests\Publication\ApiPublicationDeleteRequest;
use App\Http\ApiRequests\Publication\ApiPublicationListRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPayRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPublicListRequest;
use App\Http\ApiRequests\Publication\ApiPublicationShowForAllRequest;
use App\Http\ApiRequests\Publication\ApiPublicationShowRequest;
use App\Http\ApiRequests\Publication\ApiPublicationStoreRequest;
use App\Http\ApiRequests\Publication\ApiPublicationUpdateRequest;
use App\Http\ApiResources\Publication\PublicationResource;
use App\Repositories\Statistic\StatisticRepository;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\LMSFeedback;
use App\Models\Publication;
use App\Models\User;
use App\Models\VisitedPublication;
use App\Models\Webinar;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Publication\PublicationRepository;
use App\Services\TelegramLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\Pay\PayService;

class ApiPublicationController extends Controller
{


    /**
     * @var TelegramLogService $telegramLogService
     */
    private TelegramLogService $telegramLogService;


    /**
     * @var PublicationRepository
     */
    private PublicationRepository $publicationRepository;

    /**
     * @param PublicationRepository $publicationRepository
     * @param TelegramLogService $telegramLogService
     */
    public function __construct(
        PublicationRepository $publicationRepository,
        TelegramLogService    $telegramLogService
    )
    {
        $this->publicationRepository = $publicationRepository;
        $this->telegramLogService = $telegramLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ApiPublicationListRequest $request
     * @return ApiResponse
     */
    public function list(ApiPublicationListRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->author == null) {
            return ApiResponse::notFound('common.not_found');
        }
        $publication = Publication::where('author_id', $user->author->id)->orderBy('updated_at')->get();
        return ApiResponse::common(PublicationResource::collection($publication)->toArray($request));
    }

    /**
     * @param ApiPublicationPublicListRequest $request
     * @return ApiResponse
     */
    public function publicList(ApiPublicationPublicListRequest $request): ApiResponse
    {
        $publication = Publication::where('author_id', $request->author)->orderBy('updated_at')->get();
        return ApiResponse::common(PublicationResource::collection($publication)->toArray($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiPublicationStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiPublicationStoreRequest $request): ApiResponse
    {
        $publication = $this->publicationRepository->store($request);
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Display the specified resource.
     *
     * @param ApiPublicationShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiPublicationShowRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->author == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication = Publication::where('id', $id)->where('author_id', $user->author->id)->first();
        if ($publication === null) {
            return ApiResponse::notFound('not_found');
        }

        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiPublicationUpdateRequest $request
     * @return ApiResponse
     */
    public function update(ApiPublicationUpdateRequest $request, int $id): ApiResponse
    {
        $publication = $this->publicationRepository->update($request, $id);
        if ($publication === null) {
            ApiResponse::notFound('common.not_found');
        }
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApiPublicationDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiPublicationDeleteRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->author == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication = Publication::where('id', $id)->where('author_id', $user->author->id)->first();
        if ($publication == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication->delete();
        return ApiResponse::success();
    }

    /**
     * @param ApiPublicationShowForAllRequest $request
     * @param string $uuid
     * @return \App\Http\ApiResponses\ApiResponseCommon|\App\Http\ApiResponses\ApiResponseNotFound
     */
    public function showByUuid(ApiPublicationShowForAllRequest $request, string $uuid)
    {
        $publication = Publication::where('uuid', $uuid)->first();
        if ($publication == null) {
            return ApiResponse::notFound('common.not_found');
        }
        StatisticRepository::addViewPublication($publication->id);

        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * @param ApiPublicationPayRequest $request
     */
    public function pay(ApiPublicationPayRequest $request)
    {

        /** @var Publication $publication */
        $publication = Publication::where('uuid', '=', $request->uuid)->first();

        if ($publication === null) {
            return ApiResponse::notFound('validation.course.not_found');
        }

        $email = $request->input('email');
        $user = User::easyRegister($email);

        $successUrl = config('app.frontend_url') . '/app/auth/sign-in';
        if (request()->user('sanctum') && $user->id == request()->user('sanctum')->id) {
            $successUrl = '';
        }

        if ($user === null) {
            return ApiResponse::error('common.register_email_error');
        }

        $payment = PayService::buyPublication($publication->price, $publication, $user, $successUrl);

        if ($payment === false) {
            return ApiResponse::error('common.error_while_pay');
        }

        return ApiResponse::common(['redirect' => $payment->paymentUrl]);
    }

    public function checkFeedback(ApiCheckPostFeedbackRequest $request)
    {
        $user = Auth::user();
        $publication = $request->type === 'webinar' ? Webinar::findOrFail($request->id) : Publication::findOrFail($request->id);
        $fb = LMSFeedback::where('user_id', $user->id)->where($request->type.'_id',$publication->id)->first();
        return ApiResponse::common(['result' => $fb ? 'made_feedback' : 'not_made_feedback',
            'time_of_webinar_start' => $request->type === 'webinar' ? $publication->start_at : 'post dont have start time']);
    }
}
