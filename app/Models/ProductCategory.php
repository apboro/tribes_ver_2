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

    public const DEFAULT_CATEGORY_NAME = 'Без категории';

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
        $query = $query->withCount('product');

        if ($filter['hide_empty'] ?? false) {
            $query = self::hideEmpty($query);
        }
        $query->orderBy('id');

        return $query->get()
                ->when(self::isNeedDefaultCategory($filter), function($collection) use ($filter) {
                    return $collection->push(self::getDefaultCategoryWithShopId($filter['shop_id']));
                });
    }

    public static function countByFilter(array $filter): int
    {
        $query = self::addFilter($filter, self::getFilterRules());
        if ($filter['hide_empty'] ?? false) {
            $query = self::hideEmpty($query);
        }

        $count = $query->count() + (self::isNeedDefaultCategory($filter) ? 1 : 0);

        return $count;
    }

    private static function isNeedDefaultCategory(array $filter): bool
    {
        return ((isset($filter['shop_id'])) &&
                ((!isset($filter['hide_empty'])) || 
                ($filter['hide_empty'] && Product::isDefaultCategoryByShopId($filter['shop_id']))));
    }

    private static function getDefaultCategoryWithShopId(int $shopId): self
    {
        $self = new self();
        $self->id = 0;
        $self->parent_id = 0;
        $self->shop_id = $shopId;
        $self->name = self::DEFAULT_CATEGORY_NAME;

        return $self;
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

    public static function findWithProductCount(int $id): ?self
    {
        return self::withCount('product')->find($id);
    }

    private static function hideEmpty(Builder $query): Builder
    {
        return $query->whereHas('product', function ($query) {
            $query->whereNotIn('status', Product::NOT_SHOW_STATUS);
        });
    }
}
