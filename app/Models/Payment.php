<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Carbon\Carbon;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/** @method PaymentFactory factory() */
class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static $status = [
        'CONFIRMED' => "Подтверждён",
        'COMPLETED' => "Завершен",
        'NEW' => "Попытка оплаты",
        'REFUNDED' => "Возвращён",
    ];

    public static $types = ["payout", "tariff", "donate", "course"];

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function scopeOwned($query)
    {
        return $query->whereHas('community', function($q){
            $q->where('owner', Auth::user()->id);
        })->orWhere('author', Auth::user()->id);
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function owner()
    {
        return $this->community()->first()->owner()->first();
    }

    public function telegramUser()
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id', 'telegram_id');
    }

    public function accumulation()
    {
        return $this->belongsTo(Accumulation::class, 'SpAccumulationId', 'SpAccumulationId');
    }

    public function formattedAmount()
    {
        $formated = number_format($this->amount / 100, 2, ',', ' ');

        return $formated;
    }

    function getType()
    {
        $type = 'Тип не определен';

        switch ($this->type){
            case 'payout' :
                $type = 'Выплата';
                break;
            case 'tariff' :
                $type =  __('base.tariff');
                break;
            case 'donate' :
                $type = __('base.donation');
                break;
            case 'course' :
                $type = 'Медиаконтент';
                break;
        }

        return $type;
    }

    public function checkAccumulation($id, $owner)
    {
        $accumulation = Accumulation::where('SpAccumulationId', $id)->first();
        if($accumulation){
            return $accumulation;
        } else {
            return Accumulation::create([
                'user_id' => $owner->id,
                'SpAccumulationId' => $id,
                'started_at' => Carbon::now(),
                'ended_at' => Carbon::now()->endOfDay()->modify('last day of this month'),
                'status' => 'active',
            ]);
        }
    }

    public function tariffs()
    {
        return $this->whereHas('payable', function($q){
            $q->wherePayableType(TariffVariant::class);
        });
    }
    public function donates()
    {
        return $this->whereHas('payable', function($q){
            $q->wherePayableType(DonateVariant::class);
        });
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
