<?php

namespace App\Http\Controllers\APIv3\Market;

use App\Bundle\Telegram\MiniApp\InitDataDTO;
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

    public function getBearerTokenByTgUser(Request $request): ApiResponse
    {
        try {
            $initDataDTO = $this->validator->validate($request->header('Authorization_tma', ''));
            $tgUser = TelegramUser::where('telegram_id', $initDataDTO->user->id)->first();

            $user = Auth::guard('sanctum')->user();
            if ($user) {
                log::info('Mini app User has token');
                if ($user->id !== $tgUser->user_id) {
                    $message = 'income by bearer user id:' . $user->id . ',by tg user: ' . $tgUser->user_id;
                    log::debug('Tg relations error: ' . $message);
                }

                $userId = $user->id;
                $token = $request->bearerToken();
            } else {
                $auth = Auth::loginUsingId($tgUser->user->id);

                $userId = $auth->id;
                $token = $auth->createToken($auth->id)->plainTextToken;

                log::info('user token = ' . json_encode($this->userData['token']));
            }

            return ApiResponse::common(compact('userId', 'token'));
        } catch (Exception $e) {
            $message = $e->getMessage();
            log::error('getBearerToken error' . $message);

            return ApiResponse::error('common.create_error');
        }
    }

    public function attachTgUserToUser(Request $request): ApiResponse
    {
        try {
            $initDataDTO = $this->validator->validate($request->header('Authorization_tma', ''));
            TelegramUser::attachMiniAppUser($request->user(), $initDataDTO->user);

            return ApiResponse::success('common.success');
        } catch (Exception $e) {
            $message = $e->getMessage();
            log::error('attachTgUserToUser error' . $message);

            return ApiResponse::error('common.create_error');
        }
    }
}
