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
            $initDataDTO = $this->validator->validate($request->header('Init-data', ''));
            $tgUser = TelegramUser::where('telegram_id', $initDataDTO->user->id)->first();

            $user = Auth::guard('sanctum')->user();
            if ($user) {
                log::info('Mini app User has token');
                $tgUserId =  $tgUser->user_id ?? null;
                if ($user->id !== $tgUserId) {
                    $message = 'income by bearer user id:' . $user->id . ',by tg user: ' . $tgUserId;
                    log::debug('Tg relations error: ' . $message);
                }

                $userId = $user->id;
                $token = $request->bearerToken();
            } else {
                if (!$tgUser || $tgUser->user === null) {
                    $userId = '';
                    $token = '';
                } else {
                    $user = Auth::loginUsingId($tgUser->user->id);

                    $userId = $user->id;
                    $token = $user->createToken($user->id)->plainTextToken;

                    log::info('user token = ' . json_encode($token));
                }
            }

            $hasSubscription = (bool)($user->subscription->subscription ?? null);

            return ApiResponse::common(compact('userId', 'token', 'hasSubscription'));
        } catch (Exception $e) {
            $message = $e->getMessage();
            $line = $e->getLine();
            log::error('getBearerToken error' . $message . ' line: ' . $line);

            return ApiResponse::error('common.create_error');
        }
    }

    public function attachTgUserToUser(Request $request): ApiResponse
    {
        try {
            $initDataDTO = $this->validator->validate($request->header('Init-data', ''));
            TelegramUser::attachMiniAppUser($request->user(), $initDataDTO->user);

            return ApiResponse::success('common.success');
        } catch (Exception $e) {
            $message = $e->getMessage();
            log::error('attachTgUserToUser error' . $message);

            return ApiResponse::error('common.create_error');
        }
    }
}
