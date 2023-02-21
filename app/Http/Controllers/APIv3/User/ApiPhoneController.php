<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiConfirmCodeRequest;
use App\Http\ApiRequests\ApiConfirmPhoneRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sms\PhoneRequest;
use App\Models\User;
use App\Repositories\Author\AuthorRepositoryContract;
use App\Repositories\Notification\NotificationRepositoryContract;
use App\Repositories\Notification\Sms16Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApiPhoneController extends Controller
{

    private $authorRepo;
    private NotificationRepositoryContract $notifyRepo;

    public function __construct(
        AuthorRepositoryContract $authorRepo,
        NotificationRepositoryContract $notifyRepo
    ) {
        $this->authorRepo = $authorRepo;

        $this->notifyRepo = $notifyRepo;
    }

    /**
     * TODO Swagger annotations
     *
     *
     * @return ApiResponse
     */
    public function resetConfirmed():ApiResponse
    {
        if ($this->authorRepo->resetMobile()) {
            return ApiResponse::success('phone.reset_success');
        }
        return ApiResponse::error('phone.reset_error');
    }

    public function sendConfirmCode(ApiConfirmPhoneRequest $request):ApiResponse
    {

        if ($this->authorRepo->numberForCall($request)) {
            return ApiResponse::success('phone.message_was_sent');
        }
        return ApiResponse::error('message_sent_error');
    }


    public function confirmPhone(ApiConfirmCodeRequest $request):ApiResponse
    {

        $user = Auth::user();
        $sms = $this->notifyRepo->tryActivateAccount($user, $request->input('sms_code'));

        if(empty($sms)) {
           return ApiResponse::error('confirm_code.sms_code_not_found');
        }
        if($sms->isblocked){
            return ApiResponse::error('confirm_code.send_is_blocked');
        }
        if($sms->code != $request['sms_code']){
            return ApiResponse::error('confirm_code.code_error');
        }
        return ApiResponse::success('confirm_code.success');
    }
}
