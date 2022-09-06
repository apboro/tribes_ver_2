<?php

namespace App\Repositories\Statistic\DTO;

use App\Exceptions\StatisticException;
use Illuminate\Support\Collection;

/**
 * @todo допилить , спрятать прямое обращение к переменным, добавить метод заполнения графика
 */
class ChartData
{
    // массив временных меток, количество всегда должно совпадать с количеством значений
    private array $marks = [];
    // массив значений всегда хранится по уникальному имени
    private array $values = [];
    private array $additions = [];

    public function initChart(Collection $data)//todo переопределять через массив имен названия графиков
    {
        foreach ($data as $key => $eachObjectValues) {
            if ($key === 0) {
                $props = array_keys(get_object_vars($eachObjectValues));
            }
            if (empty($props) || !in_array('scale', $props)) {
                throw new StatisticException('В коллекции нету столбца "scale" для меток времени графика.');
            }
            $this->marks[$key] = $eachObjectValues->scale;
            foreach ($props as $eachProp) {
                if ($eachProp == 'scale') {
                    continue;
                }
                $this->values[$eachProp][$key] = $eachObjectValues->{$eachProp};
            }
        }

        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function addAdditionParam(string $name, $paramValue)
    {
        $this->additions[$name] = $paramValue;
    }

    public function includeChart(ChartData $chart)
    {
        //проверить идентичность массива меток, если не идентичны, то вложенность не разрешается
        if (!empty(array_diff_assoc($this->marks, $chart->marks))) {
            return null;
        }
        // подмешать другие графики
        $this->values = array_merge($chart->values, $this->values);
        // подмешать дополнительную информацию
        foreach ($chart->additions as $key => $eachAddition) {
            if (empty($this->additions[$key])) {
                $this->additions[$key] = $eachAddition;
            }
        }
        return $this;
    }

    public function getAdditions(): array
    {
        return $this->additions;
    }

    public function getMarks(): array
    {
        return $this->marks;
    }
}