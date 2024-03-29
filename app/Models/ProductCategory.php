<?php

namespace App\Models;

use App\Models\Traits\HasFilter;
use App\Traits\Authorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class ProductCategory extends Model
{
    use HasFactory,
        HasFilter;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public static function isBelongsShop(int $categoryId, int $shopId): bool
    {
        return (bool) self::where('id', $categoryId)->where('shop_id', $shopId)->exists();
    }

    public static function isBelongsUser(int $categoryId, int $userId): bool
    {
        $category = self::where('id', $categoryId)->first();
        if (!$category) {
            return false;
        }

        return  (bool) $category->shop->user()->where('id', $userId)->exists();
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    private static function getFilterRules(): array
    {
        return [
            'name' => ['field' => 'name', 'sql' => 'ilike'],
            'shop_id' => ['field' => 'shop_id', 'sql' => '='],
        ];
    }

    public static function findByFilter(array $filter): Collection
    {
        $query = self::addFilter($filter, self::getFilterRules());
        if ($filter['hide_empty'] ?? false) {
            $query = self::hideEmpty($query);
        }
        $query->orderBy('parent_id');

        return  $query->get();
    }

    public static function countByFilter(array $filter): int
    {
        $query = self::addFilter($filter, self::getFilterRules());
        if ($filter['hide_empty'] ?? false) {
            $query = self::hideEmpty($query);
        }

        return $query->count();
    }
   
    public function remove(): ?bool
    {
        if (self::where('parent_id', $this->id)->exists()) {
            return false;
        }
        Product::moveAllFromCategory($this->id);

        return $this->delete();
    }

    public function product(): HasMany
    {
        return $this->HasMany(Product::class, 'category_id');
    }

    private static function hideEmpty(Builder $query): Builder
    {
        return $query->whereHas('product', function ($query) {
            $query->whereNotIn('status', Product::NOT_SHOW_STATUS);
        });
    }
}
