<?php

namespace App\Models\Statistic;

use Database\Factories\Statistic\MProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $uuid Уникальный номер медиатовара
 * @property $type Тип медиа товара (по умолчанию курс)
 * @property $c_uniq_buyers Количество покупателей купивших товар
 * @property $c_time_view Количество секунд просмотра товара
 *
 * @method MProductFactory factory()
 */
class MProduct extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'uuid';

    public function mProductSales(): HasMany
    {
        return $this->hasMany(MProductSale::class,'uuid','uuid');
    }

    public function MProductUserViews(): HasMany
    {
        return $this->hasMany(MProductUserView::class,'uuid','uuid');
    }

}
