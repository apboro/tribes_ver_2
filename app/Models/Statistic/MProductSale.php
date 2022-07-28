<?php

namespace App\Models\Statistic;

use App\Filters\QueryFilter;
use App\Models\Payment;
use App\Models\TelegramUser;
use App\Models\User;
use Database\Factories\Statistic\MProductSaleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $payment_id идентификатор платежа
 * @property $uuid уникальный номер медиатовара
 * @property $user_id идентификатор покупателя
 * @property $price стоимость на начало покупки
 * @property $created_at
 * @property $updated_at
 *
 * @method MProductSaleFactory factory()
 */
class MProductSale extends Model
{
    use HasFactory;

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public $incrementing = false;
    protected $primaryKey = 'payment_id';

    public function mProduct(): BelongsTo
    {
        return $this->belongsTo(MProduct::class, 'uuid','uuid');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function teleUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'user_id','user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id','id');
    }
}
