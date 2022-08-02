<?php

namespace App\Models\Traits;

use App\Models\Statistic\MProduct;
use App\Models\Statistic\MProductSale;
use App\Models\Statistic\MProductUserView;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait MProductable
{
    public function mediaStatistic(): HasOne
    {
        return $this->hasOne(MProduct::class,'uuid', 'uuid');
    }

    public function mediaSales(): HasMany
    {
        return $this->hasMany(MProductSale::class,'uuid', 'uuid');
    }

    public function mediaViews(): HasMany
    {
        return $this->hasMany(MProductUserView::class,'uuid', 'uuid');
    }
}

