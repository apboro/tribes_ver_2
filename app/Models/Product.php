<?php

namespace App\Models;

use App\Traits\Authorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property $price
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const HOW_SHOW_DEFAULT = 10;

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_id');
    }

    private static function addFilter(array $filter): Builder
    {
        // $options = [ 'Параметр запроса' => ['field' => 'поле в БД', 'sql' => 'операция'] ]
        $options = [
            'authorId' => ['field' => 'author_id', 'sql' => '='],
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
    public static function findByFilter(array $filter): Collection
    {
        return self::addFilter($filter)
            ->offset($filter['offset'] ?? 0)
            ->limit($filter['limit'] ?? self::HOW_SHOW_DEFAULT)
            ->get();
    }

    public static function countByFilter(array $filter): int
    {
        return self::addFilter($filter)->count();
    }
}
