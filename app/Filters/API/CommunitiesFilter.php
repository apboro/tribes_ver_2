<?php

namespace App\Filters\API;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @property EloquentBuilder $builder
 * @method EloquentBuilder apply(EloquentBuilder $builder)
 */
class CommunitiesFilter extends QueryFilter
{
    const CHAT_TYPE_GROUP = 1;
    const CHAT_TYPE_CHANNEL = 2;

    public function typeList()
    {
        return [
            self::CHAT_TYPE_GROUP => 'group',
            self::CHAT_TYPE_CHANNEL => 'channel',
        ];
    }

    public function filters(): array
    {
        return $this->request->get('filter', []);
    }
//
    /**
     * filter[type]
     * @param int $value enumerate 0,1
     * @return EloquentBuilder
     */
    public function type($value): EloquentBuilder
    {
        $value = (int)$value;
        if (array_key_exists($value, $this->typeList())) {
            return $this->builder->whereHas('connection', function ($query) use ($value) {
                return $query->where('chat_type', $this->typeList()[$value]);
            });
        } else {
            //todo добавить throw new ApiException('Не верное значение фильтра')
            return $this->builder;
        }

    }
}