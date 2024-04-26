<?php

namespace App\Http\Controllers\APIv3\Market;

use App\Bundle\Telegram\MiniApp\Validator\InitDataValidator;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Log;

class TgUserPassportController extends Controller
{
    private $validator;

    public function __construct(InitDataValidator $initDataValidator)
    {
        $this->validator = $initDataValidator;
    }

    public function getBearerToken(Request $request): ApiResponse
    {
        try {
            $rawData = $request->header('Authorization');
            $initDataDTO = $this->validator->validate($rawData);
            $token = '';

            $tgUser = TelegramUser::where('telegram_id', $initDataDTO->user->id)->first();
            if ($tgUser) {
                $auth = Auth::loginUsingId($tgUser->user->id);
                $token = $auth->createToken($auth->id)->plainTextToken;
                log::info('user token = ' . $token);
            }

            return ApiResponse::common(['token' => $token]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            log::error('has shop error' . $message);

            return ApiResponse::error('common.create_error');
        }
    }
}
