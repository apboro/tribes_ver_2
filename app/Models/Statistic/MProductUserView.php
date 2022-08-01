<?php

namespace App\Models\Statistic;

use App\Models\User;
use Database\Factories\Statistic\MProductUserViewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $uuid уникальный номер медиатовара
 * @property $user_id идентификатор покупателя
 * @property $c_time_view Количество секунд просмотра товара
 *
 * @method MProductUserViewFactory factory()
 */
class MProductUserView extends Model
{
    use HasFactory;

    public function mProduct(): BelongsTo
    {
        return $this->belongsTo(MProduct::class, 'uuid','uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
