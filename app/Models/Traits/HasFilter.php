<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    private static function addFilter(array $filter, array $options): Builder
    {
        $query = self::query();
        foreach ($filter as $name => $value) {
            if (isset($options[$name])) {
                if ($options[$name]['sql'] == 'ilike') {
                    $value = '%' . $value . '%';
                }

                if ($options[$name]['sql'] == 'in') {
                    $query->whereIn($options[$name]['field'], $value);
                } else {
                    $query->where($options[$name]['field'], $options[$name]['sql'], $value);
                }
            }
        }

        return $query;
    }
}
