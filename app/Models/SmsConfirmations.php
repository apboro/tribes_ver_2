<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsConfirmations extends Model
{
    use HasFactory;

    protected $table = 'sms_confirmation';

    public $fields = [
        'partner_id',
        'company_id',
        'phone',
        'code',
        'status_code',
        'sms_id',
        'cost',
        'ip',
        'message',
    ];

    protected $guarded = [];

    public function attempt()
    {
        $this->attempts += 1;
        if($this->attempts > env('SMS_MAX_ATTEMPTS', 4)){
            $this->isblocked = true;
        }

        return $this->save();
    }

    // public function isConfirmed()
    // {
    //     return $this->confirmed;
    // }

    // public function isBlocked()
    // {
    //     return $this->isblocked;
    // }

    public function confirm()
    {
        $user = $this->user()->first();
        if($user){
            $this->confirmed = true;
            $user->phone_confirmed = true;
            $user->save();
        }

        $sms = $this->save();

        return $sms;
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
