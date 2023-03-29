<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\Profile\ApiConfirmPhoneRequest;
use App\Http\ApiRequests\Profile\ApiResetPhoneRequest;
use App\Http\ApiRequests\Profile\ApiSendConfirmCodeRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseServerError;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Author\AuthorRepositoryContract;
use App\Repositories\Notification\NotificationRepositoryContract;
use Illuminate\Support\Facades\Auth;

class ApiUserPhoneController extends Controller
{

    private AuthorRepositoryContract $authorRepo;

    private NotificationRepositoryContract $notifyRepo;

    public function __construct(AuthorRepositoryContract $authorRepo, NotificationRepositoryContract $notifyRepo)
    {
        $this->authorRepo = $authorRepo;
        $this->notifyRepo = $notifyRepo;
    }

    /**
     *
     * @return ApiResponse
     */
    public function resetConfirmed(ApiResetPhoneRequest $request): ApiResponse
    {
        if ($this->authorRepo->resetMobile()) {
            return ApiResponse::success('phone.reset_success');
        }

        return ApiResponse::error('phone.reset_error');
    }

    /**
     *
     * @param ApiSendConfirmCodeRequest $request
     *
     * @return ApiResponse
     */
    public function sendConfirmCode(ApiSendConfirmCodeRequest $request): ApiResponse
    {
        if ($this->authorRepo->numberForCall($request)) {
            return ApiResponse::success('phone.message_was_sent');
        }

        return new ApiResponseServerError();
    }

    /**
     *
     * @param ApiConfirmPhoneRequest $request
     *
     * @return ApiResponse
     */
    public function confirmPhone(ApiConfirmPhoneRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $sms = $this->notifyRepo->tryActivateAccount($user, $request->input('sms_code'));

        if (empty($sms)) {
            return ApiResponse::validationError()->addError('sms_code', 'phone.confirm_sms_code_not_found');
        }

        if ($sms->isblocked) {
            return ApiResponse::validationError()->addError('sms_code', 'phone.confirm_send_is_blocked');
        }

        if ((string)$sms->code !== $request['sms_code']) {
            return ApiResponse::validationError()->addError('sms_code', 'phone.confirm_code_error');
        }

        return ApiResponse::success('phone.confirm_success');
    }
}
