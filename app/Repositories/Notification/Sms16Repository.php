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
            if($sms->code != null && $sms->code == (int)$code && !$sms->isblocked){
                $user->phone = $sms->phone;
                $user->code = $sms->phone_code;
                $user->save();     
                           
                $sms->confirm();

                $sms->attempts = 0;
                $sms->save();
            } else {
                $sms->attempt();
            }

            return $sms;
        } else {

            return false;
        }
    }

    public static function sendConfirmationTo($user, $phoneCode, $phone)
    {
        $phoneNumber = $phoneCode . $phone;
        $code = rand(1000, 9999);
        $message = new SmsService();
        $sms = $message->sendMessage($phoneNumber, 'Код подтверждения ' . env('APP_NAME') . ':' . $code);

        if (isset($sms[0][$phoneNumber]['error']) && $sms[0][$phoneNumber]['error'] == 'phone_code_user') {
            Log::debug('Предположительно на sms16.ru закончились деньги. ' . 'Ответ сервиса: ' .$sms[0][$phoneNumber]['error']);

            return false;
        }
        if (isset($sms[0][$phoneNumber]['error']) && $sms[0][$phoneNumber]['error'] === "0") {

           // SmsConfirmation::where('user_id', $user->id)->delete();

            $sms_confirmation = SmsConfirmation::firstOrNew(['user_id' => $user->id]);
            
            $sms_confirmation->fill([
                'user_id' => $user->id,
                'phone' => $phone,
                'phone_code' => $phoneCode,
                'code' => $code,
                'status' => 'OK',
                'sms_id' => $sms[0][$phoneNumber]['id_sms'],
                'cost' => $sms[0][$phoneNumber]['cost'],
                'ip' => request()->ip(),
                'isblocked' => false,
                'attempts' => 0
            ]);

            $sms_confirmation->save();
        }

        $balance = $message->getBalance();
        if (isset($balance['money']) && $balance['money'] < '20') {
            Log::info('На sms16.ru осталось менее 20 рублей, пожалуйста пополните счёт.');
        }

        return $sms;
    }


}