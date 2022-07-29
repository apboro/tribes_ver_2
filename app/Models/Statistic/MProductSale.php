<?php

namespace App\Models\Statistic;

use App\Models\Payment;
use App\Models\User;
use Database\Factories\Statistic\MProductSaleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $payment_id идентификатор платежа
 * @property $uuid уникальный номер медиатовара
 * @property $user_id идентификатор покупателя
 * @property $price стоимость на начало покупки
 *
 * @method MProductSaleFactory factory()
 */
class MProductSale extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'payment_id';

    public function mProduct(): BelongsTo
    {
        return $this->belongsTo(MProduct::class, 'uuid','uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id','id');
    }
}
