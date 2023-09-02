<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Webinars\ApiWebinarsDeleteRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarShowByUuidRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsListRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsPublicListRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsSetUserRoleRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsShowRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsStoreRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsUpdateRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarPayRequest;
use App\Http\ApiResources\Webinar\WebinarCollection;
use App\Http\ApiResources\Webinar\WebinarResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Webinar\WebinarRepository;
use App\Models\Webinar;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\Tinkoff\Payment;
use App\Services\WebinarService;

class ApiWebinarController extends Controller
{

    const DEFAULT_OFFSET = 0;
    const DEFAULT_COUNT_WEBINARS = 3;

    private WebinarRepository $webinarRepository;
    private WebinarService $webinarService;

    /**
     * @param WebinarRepository $webinarRepository
     */

    public function __construct(WebinarRepository $webinarRepository, WebinarService $webinarService)
    {
        $this->webinarRepository = $webinarRepository;
        $this->webinarService = $webinarService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ApiWebinarsListRequest $request
     * @return ApiResponse
     */
    public function list(ApiWebinarsListRequest $request): ApiResponse
    {
        $webinars = $this->webinarRepository->list($request);
        $count = $webinars->count();
        $webinars = $webinars
        ->skip($request->offset ?? 0)
        ->take($request->limit ?? 3)
        ->orderBy('created_at', 'DESC')->get();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items(new WebinarCollection($webinars));
    }

    public function publicList(ApiWebinarsPublicListRequest $request): ApiResponse
    {
        $webinars = $this->webinarRepository->publicList($request);
        $count = $webinars->count();
        $webinars = $webinars
            ->skip($request->offset ?? 0)
            ->take($request->limit ?? 3)
            ->orderBy('created_at', 'DESC')->get();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items(new WebinarCollection($webinars));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ApiWebinarsStoreRequest $request
     *
     * @return ApiResponse
     */
    public function store(ApiWebinarsStoreRequest $request): ApiResponse
    {
        /**@var User $user */
        $user = Auth::user();

        try {
            $webinar = $this->webinarRepository->add($request);
            $this->webinarService->setWebinarRole($webinar->external_id, $user, 'admin');

            if ($webinar === null) {
                return ApiResponse::error('add_error');
            }

            return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
        } catch (Exception $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true, );
            $errorMessage = $error['errors']['room'][0] ?? 'another error by wbnr';
            log::error('webinar store Exception:'.  $errorMessage);

            return  ApiResponse::error($errorMessage);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ApiWebinarsShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiWebinarsShowRequest $request, int $id): ApiResponse
    {
        $webinar = $this->webinarRepository->show($id);

        if ($webinar === null) {
            return ApiResponse::notFound('common.not_found');
        }

        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    public function registerWbnrUser(ApiWebinarsSetUserRoleRequest $request, string $uuid)
    {
        /**@var User $user */
        $user = Auth::user();

        /** @var Webinar $webinar */
        $webinar = $this->webinarRepository->showByUuid($uuid);

        $role = $webinar->getUserRole($user->id);
        $redirect = $this->webinarService->setWebinarRole($webinar->external_id, $user, $role);

        $webinar->redirectUrl = $redirect;

        if ($webinar === null) {
            return ApiResponse::notFound('common.not_found');
        }

        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiWebinarsUpdateRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function update(ApiWebinarsUpdateRequest $request, int $id): ApiResponse
    {
        $webinar = $this->webinarRepository->update($request, $id);

        if ($webinar === null) {
            return ApiResponse::error('add_error');
        }

        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApiWebinarsDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiWebinarsDeleteRequest $request, int $id): ApiResponse
    {
        $result = $this->webinarRepository->delete($id);
        if (!$result) {
            return ApiResponse::error('Ошибка удаления');
        }
        return ApiResponse::success();
    }


    /**
     * Show by uuid
     *
     * @param ApiWebinarShowByUuidRequest $request
     * @param string $id
     *
     * @return ApiResponse
     */
    public function showByUuid(ApiWebinarShowByUuidRequest $request, string $id): ApiResponse
    {
        $webinar = $this->webinarRepository->showByUuid($id);

        if ($webinar === null) {
            return ApiResponse::notFound('common.not_found');
        }

        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    /**
     * @param ApiWebinarPayRequest $request
     */
    public function pay(ApiWebinarPayRequest $request)
    {
        $webinar = Webinar::where('uuid', '=', $request->uuid)->first();

        if ($webinar === null) {
            return ApiResponse::notFound('validation.course.not_found');
        }

        $user = User::easyRegister($request->input('email'));

        if ($user === null) {
            return ApiResponse::error('common.user_create_error');
        }

        $tinkoffPayment = new Payment();
        $payment = $tinkoffPayment->doPayment($user, $webinar, $webinar->price, '');

        if ($payment === false) {
            return ApiResponse::error('common.error_while_pay');
        }

        return ApiResponse::common(['redirect' => $payment->paymentUrl]);
    }

}
