<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    const HOW_SHOW_DEFAULT = 10;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setAttributeUuid();
        });
    }

    public function setAttributeUuid()
    {
        $this->attributes['uuid'] = Str::uuid();
    }

    public static function findByUUID(string $uuid): ?self
    {
        return self::where('uuid', $uuid)->first();
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
