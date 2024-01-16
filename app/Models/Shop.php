<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use App\Models\Traits\HasFilter;

class Shop extends Model
{
    use HasFactory, HasFilter;

    public $guarded = [];

    public const LIMIT_SHOW_DEFAULT = 10;

    private static function getFilterRules(): array
    {
        return [
            'name' => ['field' => 'name', 'sql' => 'ilike'],
            'userId' => ['field' => 'user_id', 'sql' => '=']
        ];
    }

    public static function findByFilter(array $filter): Collection
    {
        return self::addFilter($filter, self::getFilterRules())
            ->orderByDesc('id')
            ->offset($filter['offset'] ?? 0)
            ->limit($filter['limit'] ?? self::LIMIT_SHOW_DEFAULT)
            ->get();
    }

    public static function countByFilter(array $filter): int
    {
        return self::addFilter($filter, self::getFilterRules())->count();
    }
}
