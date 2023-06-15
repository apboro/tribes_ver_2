<?php

namespace App\Http\Controllers\APIv3\Courses;

use App\Events\ApiUserRegister;
use App\Events\BuyCourse;
use App\Helper\PseudoCrypt;
use App\Http\ApiRequests\Course\ApiCourseListRequest;
use App\Http\ApiRequests\Course\ApiCoursePayRequest;
use App\Http\ApiRequests\Course\ApiCourseShowForAllRequest;
use App\Http\ApiRequests\Course\ApiCourseShowRequest;
use App\Http\ApiRequests\Course\ApiCourseStoreRequest;
use App\Http\ApiRequests\Course\ApiCourseUpdateRequest;
use App\Http\ApiResources\CourseCollection;
use App\Http\ApiResources\CourseResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Course\CourseRepository;
use App\Services\TelegramLogService;
use App\Services\Tinkoff\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiCourseController extends Controller
{
    /**
     * @var CourseRepository $courseRepository
     */
    private CourseRepository $courseRepository;

    /**
     * @var TelegramLogService $telegramLogService
     */
    private TelegramLogService $telegramLogService;


    /**
     * @var Payment $tinkoff_payment
     */
    private Payment $tinkoff_payment;


    /**
     * @param CourseRepository $courseRepository
     */

    public function __construct(
        CourseRepository   $courseRepository,
        TelegramLogService $telegramLogService,
        Payment            $tinkoff_payment
    )
    {
        $this->courseRepository = $courseRepository;
        $this->telegramLogService = $telegramLogService;
        $this->tinkoff_payment = $tinkoff_payment;
    }

    /**
     * Show list of courses
     *
     * @param ApiCourseListRequest $request
     * @return ApiResponse
     */

    public function index(ApiCourseListRequest $request): ApiResponse
    {
        /** @var Course $courses */
        $courses = Course::where('owner', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return ApiResponse::list()
            ->items(CourseCollection::make($courses)->toArray($request));
    }


    /**
     * Create a new course
     *
     * @param ApiCourseStoreRequest $request
     * @return ApiResponse
     */

    public function store(ApiCourseStoreRequest $request): ApiResponse
    {
        /** @var Course $courses */
        $course = Course::create([
            'owner' => Auth::user()->id,
            'title' => 'Новый курс'
        ]);
        return ApiResponse::common(CourseResource::make($course)->toArray($request));
    }

    /**
     * Show course by id
     *
     * @param int $id
     * @param ApiCourseShowRequest $request
     *
     * @return ApiResponse
     */

    public function show(ApiCourseShowRequest $request, int $id): ApiResponse
    {
        /** @var Course $courses */
        $course = Course::where('id', '=', $id)->first();

        if ($course === null) {
            return ApiResponse::notFound('validation.course.not_found');
        }
        return ApiResponse::common(CourseResource::make($course)->toArray($request));
    }

    /**
     * @param ApiCourseUpdateRequest $request
     * @param int $course_id
     * @return ApiResponse
     */
    public function update(ApiCourseUpdateRequest $request, int $course_id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Course $course */
        $course = Course::where('id', '=', $course_id)->
        where('owner', '=', $user->id)->first();

        if ($course === null) {
            return ApiResponse::notFound('validation.course.not_found');
        }

        $course = $this->courseRepository->update(
            $course,
            $request,
            [
                'title' => $request->input('title'),
                'cost' => $request->input('cost'),
                'access_days' => $request->input('access_days'),
                'isPublished' => $request->boolean('is_published'),
                'isActive' => $request->boolean('is_active'),
                'isEthernal' => $request->boolean('is_ethernal'),
                'payment_title' => $request->input('payment_title'),
                'payment_description' => $request->input('payment_description'),
                'preview' => $request->input('preview'),
                'thanks_text' => $request->input('thanks_text'),
                'activation_date' => $request->input('activation_date'),
                'deactivation_date' => $request->input('deactivation_date'),
                'publication_date' => $request->input('publication_date'),
                'shipping_noty' => $request->input('shipping_noty'),
                'owner' => $user->id
            ]
        );


        if ($course === null) {
            return ApiResponse::error('common.course_update_error');
        }
        return ApiResponse::common(CourseResource::make($course)->toArray($request));
    }


    /**
     * @param ApiCoursePayRequest $request
     * @return ApiResponse
     */

    public function pay(ApiCoursePayRequest $request): ApiResponse
    {
        $id = (int)PseudoCrypt::unhash($request->get('hash'));

        /** @var Course $course */
        $course = Course::where('id', '=', $id)->first();

        if ($course === null) {
            return ApiResponse::notFound('validation.course.not_found');
        }

        $course->increment('clicks');
        $password = Str::random(8);
        $email = $request->input('email');


        /** @var User $user */

        $user = $this->easy_register_user($email, $password);

        if ($user === null) {
            return ApiResponse::error('common.user_create_error');
        }

        if ($course->cost === 0) {
            $user->courses()->attach($course->id, [
                'cost' => $course->cost,
                'byed_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addDays($course->isEthernal ? 3650 : $course->access_days),
            ]);

            Event::dispatch(new BuyCourse($course, $user));

            return ApiResponse::common([
                'redirect' => $course->successPageLink()
            ]);
        }

        $payment = $this->tinkoff_payment->doPayment($user, $course, $course->cost * 100);

        if ($payment === false) {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты тарифа произошла ошибка Payment'
            );
            return ApiResponse::error('common.error_while_pay')->payload(
                [
                    'redirect' => $course->successPageLink()
                ]
            );
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

    /**
     * @param ApiCourseShowForAllRequest $request
     * @return ApiResponse
     */
    public function show_for_all(ApiCourseShowForAllRequest $request): ApiResponse
    {

        /** @var Course $course */
        $course = Course::where('id', '=', $request->input('id'))->first();
        if ($course === null) {
            return ApiResponse::notFound('validation.course.not_found_for_all');
        }

        return ApiResponse::common(CourseResource::make($course)->toArray($request));
    }

}
