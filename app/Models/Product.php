<?php

namespace App\Models;

use App\Traits\Authorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property $price
 */
class Product extends Model
{
    use HasFactory;

    public const ACTIVE_TYPE = 1;
    public const ARCHIVED_TYPE = 2;
    public const DISABLED_TYPE = 3;

    public const NOT_SHOW_STATUS = [
        self::DISABLED_TYPE
    ];

    public const HIDE_STATUS_LIST = [];

    public const STATUS_NAMES_LIST = [
        self::ACTIVE_TYPE   => 'active',
        self::ARCHIVED_TYPE => 'archived',
        self::DISABLED_TYPE => 'disabled',
    ];

    protected $guarded = [];

    public const HOW_SHOW_DEFAULT = 10;

    protected $casts = [
        'images' => 'array'
    ];

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    private static function addFilter(array $filter): Builder
    {
        // $options = [ 'Параметр запроса' => ['field' => 'поле в БД', 'sql' => 'операция'] ]
        $options = [
            'shop_id' => ['field' => 'shop_id', 'sql' => '='],
            'category_id' => ['field' => 'category_id', 'sql' => '='],
            'title' => ['field' => 'title', 'sql' => 'ilike'],
            'description' => ['field' => 'description', 'sql' => 'ilike'],
        ];

        $query = self::query();
        foreach ($filter as $name => $value) {
            if (isset($options[$name])) {
                if ($options[$name]['sql'] == 'ilike') {
                    $value = '%' . $value . '%';
                }
                $query->where($options[$name]['field'], $options[$name]['sql'], $value);
            }
        }

        return $query;
    }

    /*
     * Метод возвращает коллекцию при поиске по массиву $filter
     */
    public static function findByFilter(array $filter, array $statusList): Collection
    {
        return self::addFilter($filter)
            ->whereNotIn('status', $statusList)
            ->with('category')
            ->offset($filter['offset'] ?? 0)
            ->limit($filter['limit'] ?? self::HOW_SHOW_DEFAULT)
            ->get();
    }

    public static function countByFilter(array $filter, $statusList): int
    {
        return self::addFilter($filter)->whereNotIn('status', $statusList)->count();
    }

    public static function prepareImageRecord(int $id, string $path): array
    {
        return ['id' => $id, 'file' => $path];
    }

    /**
     * Добавляет изображение в товар
     */
    public function addImage(string $file): array
    {
        $images = $this->images ? $this->images : [];
        $maxKey = (int)array_reduce($images,
                    fn ($max, $item) => $max < $item['id'] ? $item['id'] : $max) + 1;

        $images[] = self::prepareImageRecord($maxKey, $file);
        $this->images = $images;
        $this->save();

        return ['id' => $maxKey, 'file' => $file];
    }

    /**
     * Удаляет изображение из товара
     */
    public function removeImage(int $imageId): bool
    {
        if (!$this->images) {
            return false;
        }

        $images = array_filter(
            $this->images,
            function ($item) use ($imageId) {
                return $item['id'] !== $imageId;
            }
        );
        $this->images = array_values($images);
        $this->save();

        return true;
    }

    public function setFirstImage(int $imageId): void
    {
        $images = array_filter($this->images, fn ($item) => $item['id'] === $imageId) +
                  array_filter($this->images, fn ($item) => $item['id'] !== $imageId);
        $this->images = array_values($images);
        $this->save();
    }

    public static function moveAllFromCategory(int $oldCategoryId, int $newCategoryId = 0): int
    {
        return self::where('category_id', $oldCategoryId)->update(['category_id' => $newCategoryId]);
    }

    public function canMoveToCategory(int $newCategoryId): bool
    {
        return ($newCategoryId != 0 && 
                ProductCategory::isBelongsShop($newCategoryId, $this->shop_id) === false) ?
                false : true;
    }

    public static function resolveStatus(int $id): int
    {
        return self::STATUS_NAMES_LIST[$id] ? $id : self::ACTIVE_TYPE;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}