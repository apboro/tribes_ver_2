<?php

namespace App\Repositories\Notification;

use App\Http\ApiResponses\ApiResponse;
use App\Models\SmsConfirmations as SmsConfirmation;
use App\Services\SMS16 as SmsService;
use App\Services\TelegramLogService;
use Illuminate\Support\Facades\Log;
use stdClass;

class Sms16Repository implements NotificationRepositoryContract
{
    public function send($phone, $message)
    {
        
    }

    public function tryActivateAccount($user, $code)
    {

        if(!$user)
            return false;

        $sms = SmsConfirmation::where('user_id', $user->id)->first();
        if($sms){
            $sms->attempt();

            if($sms->phone == $user->getPhone() && $sms->code != null && $sms->code == (int)$code){
                $sms->confirm();
            } else {
                $user->phone = null;
                $user->code = null;
                $user->phone_confirmed = false;
                $user->save();
            }

            return $sms;
        } else {

            return false;
        }
    }

    public static function sendConfirmationTo($user, $phone)
    {
        $code = rand(1000, 9999);
        $message = new SmsService();
        $sms = $message->sendMessage($phone, 'Код подтверждения ' . env('APP_NAME') . ':' . $code);

        if (isset($sms[0][$phone]['error']) && $sms[0][$phone]['error'] == 'phone_code_user') {
            Log::debug('Предположительно на sms16.ru закончились деньги. ' . 'Ответ сервиса: ' .$sms[0][$phone]['error']);

            return false;
        }
        if (isset($sms[0][$phone]['error']) && $sms[0][$phone]['error'] === "0") {

            $sms_confirmation = SmsConfirmation::firstOrNew(['user_id' => $user->id]);

            if ($sms_confirmation->exists) {
                $sms_confirmation->attempt();
            }
            
            $sms_confirmation->fill([
                'user_id' => $user->id,
                'phone' => $phone,
                'code' => $code,
                'status' => 'OK',
                'sms_id' => $sms[0][$phone]['id_sms'],
                'cost' => $sms[0][$phone]['cost'],
                'ip' => request()->ip(),
            ]);

            $sms_confirmation->save();
        }

        $balance = $message->getBalance();
        if (isset($balance['money']) && $balance['money'] < '20') {
            TelegramLogService::staticSendLogMessage('На sms16.ru осталось менее 20 рублей, пожалуйста пополните счёт.');
        }

        return $sms;
    }


}