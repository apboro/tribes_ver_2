<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use App\Models\Publication;
use App\Models\Webinar;
use App\Models\Traits\HasFilter;
use Illuminate\Support\Facades\DB;

class Author extends Model
{
    use HasFactory, HasFilter;

    public $guarded = [];

    public const HOW_SHOW_DEFAULT = 10;

    public function publications()
    {
     return $this->hasMany(Publication::class);   
    }

    public function webinars()
    {
     return $this->hasMany(Webinar::class);   
    }

    public function isUserAuthor(int $userId): bool
    {
       return Author::where('user_id', $userId)->first() !== null;
    }

    private static function getFilterRules(): array
    {
        return ['name' => ['field' => 'name', 'sql' => 'ilike']];
    }

    private static function findOnlyWithProducts(Builder $query): void
    {
        $query->select(DB::raw(1))
            ->from('products')
            ->whereColumn('authors.id', 'products.author_id')
            ->limit(1);
    }

    public static function findAuthorsWithProducts(array $filter): Collection
    {
       return self::addFilter($filter, self::getFilterRules())
            ->whereExists(function ($query) {
                self::findOnlyWithProducts($query);
                })
            ->offset($filter['offset'] ?? 0)
            ->limit($filter['limit'] ?? self::HOW_SHOW_DEFAULT)
            ->get();
    }   
    
    public static function countAuthorsWithProducts(array $filter): int
    {
        return self::addFilter($filter, self::getFilterRules())
            ->whereExists(function ($query) {
                self::findOnlyWithProducts($query);
            })->count();
    }
}
