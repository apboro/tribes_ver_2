<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Events\ApiUserRegister;
use App\Events\BuyCourse;
use App\Events\BuyPublicaionEvent;
use App\Http\ApiRequests\Publication\ApiPublicationDeleteRequest;
use App\Http\ApiRequests\Publication\ApiPublicationListRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPayRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPublicListRequest;
use App\Http\ApiRequests\Publication\ApiPublicationShowForAllRequest;
use App\Http\ApiRequests\Publication\ApiPublicationShowRequest;
use App\Http\ApiRequests\Publication\ApiPublicationStoreRequest;
use App\Http\ApiRequests\Publication\ApiPublicationUpdateRequest;
use App\Http\ApiResources\Publication\PublicationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\User;
use App\Models\VisitedPublication;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Publication\PublicationRepository;
use App\Services\TelegramLogService;
use App\Services\Tinkoff\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiPublicationController extends Controller
{


    /**
     * @var TelegramLogService $telegramLogService
     */
    private TelegramLogService $telegramLogService;


    /**
     * @var Payment $tinkoff_payment
     */
    private Payment $tinkoff_payment;

    /**
     * @var PublicationRepository
     */
    private PublicationRepository $publicationRepository;

    /**
     * @param PublicationRepository $publicationRepository
     * @param TelegramLogService $telegramLogService
     * @param Payment $tinkoff_payment
     */
    public function __construct(
        PublicationRepository $publicationRepository,
        TelegramLogService $telegramLogService,
        Payment            $tinkoff_payment
    )
    {
        $this->publicationRepository = $publicationRepository;
        $this->telegramLogService = $telegramLogService;
        $this->tinkoff_payment = $tinkoff_payment;
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
        $publication = Publication::where('author_id', $user->author->id)->get();
        return ApiResponse::common(PublicationResource::collection($publication)->toArray($request));
    }

    /**
     * @param ApiPublicationPublicListRequest $request
     * @return ApiResponse
     */
    public function publicList(ApiPublicationPublicListRequest $request): ApiResponse
    {
        $publication = Publication::where('author_id', $request->author)->get();
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

        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * @param ApiPublicationPayRequest $request
     * @return ApiResponse
     */
    public function pay(ApiPublicationPayRequest $request): ApiResponse
    {        

        /** @var Publication $publication */
        $publication = Publication::where('uuid', '=', $request->input('uuid'))->first();

        if ($publication === null) {
            return ApiResponse::notFound('validation.course.not_found');
        }

        $password = Str::random(8);
        $email = $request->input('email');

        /** @var User $user */

        $user = $this->easy_register_user($email, $password);

        if ($user === null) {
            return ApiResponse::error('common.user_create_error');
        }

        if ($publication->price === 0 || $publication->price === null) {
            $user->publications()->attach($publication->id, [
                'cost' => $publication->price === null ? 0 : $publication->price,
                'byed_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addDays(365),
            ]);

            Event::dispatch(new BuyPublicaionEvent($publication, $user));

            return ApiResponse::success();
        }

        $payment = $this->tinkoff_payment->doPayment($user, $publication, $publication->price * 100,'');

        if ($payment === false) {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты тарифа произошла ошибка Payment'
            );
            return ApiResponse::error('common.error_while_pay');
        }
        return ApiResponse::common([
            'redirect' => $payment->paymentUrl
        ]);
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function easy_register_user(string $email, string $password): User
    {

        /** @var User $user */

        $user = User::where('email', $email)->first();

        if ($user !== null) {
            return $user;
        }

        /** @var User $user */
        $user = User::create([
            'email' => strtolower($email),
            'name' => explode('@', $email)[0],
            'code' => 0000,
            'phone' => null,
            'password' => Hash::make($password),
            'phone_confirmed' => false,
        ]);

        $user->tinkoffSync();

        Event::dispatch(new ApiUserRegister($user, $password));

        return $user;
    }
}
