<?php

namespace App\Repositories\Notification;

use App\Models\SmsConfirmations as SmsConfirmation;
use App\Services\SMS as SmsService;
use stdClass;

class SmsRepository implements NotificationRepositoryContract
{

    public function send($phone, $message)
    {
        $smsru = new SmsService();
        $data = new stdClass();
        $data->to = $phone;
        $data->text = $message;
        $data->from = 'SmsService';
        $data->test = 0;
        $sms = $smsru->send_one($data);
    }

    public function tryActivateAccount($user, $code)
    {
        if(!$user)
            return false;

        $sms = SmsConfirmation::where('user_id', $user->id)->first();
        if($sms){
            $sms->attempt();

            if($sms->code != null && $sms->code == (int)$code){
                $sms->confirm();
            }

            return $sms;
        } else {
            return false;
        }

    }

    public static function sendConfirmationTo($user, $phoneCode, $phone)
    {

        $phoneNumber = $phoneCode . $phone;
        $smsru = new SmsService();
        $data = self::prepareSmsData($phoneNumber);
        
        $sms = $smsru->phoneSendOne($data);
        
        if ($sms->status == "OK") {
            $sms_confirmation = SmsConfirmation::firstOrNew(['user_id' => $user->id]);

            if ($sms_confirmation->exists) {
                $sms_confirmation->attempt();
            }
            
            $sms_confirmation->fill([
                'user_id' => $user->id,
                'phone' => $phone,
                'phone_code' => $phoneCode,
                'code' => $sms->code,
                'status' => $sms->status,
                'sms_id' => $sms->call_id,
                'cost' => $sms->cost,
                'ip' => request()->ip(),
            ]);

            $sms_confirmation->save();
        }
        return $sms;
    }

    private static function prepareSmsData($phone)
    {
        $data = new stdClass();
        $data->phone = $phone;
        $data->ip = request()->ip();

        return $data;
    }
}
