<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\SubscriptionMade;
use Illuminate\Support\Facades\Event;

/**
 *  @property int $id
 */
class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden=['created_at','updated_at'];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public static function actionAfterPayment($payment)
    {
        Event::dispatch(new SubscriptionMade($payment->payer, $payment->payable));
    }

    public static function getAvailableSubscriptions(?User $user): Collection
    {
        return self::orderBy('id')
            ->when($user && $user->isUsedTrialSubscription(), function ($q) {
                return $q->where('id', '!=', UserSubscription::TRIAL_PLAN_ID);
            })
            ->get();
    }
}
