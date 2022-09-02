<?php

namespace App\Repositories\Statistic\DTO;
/**
 * @todo допилить , спрятать прямое обращение к переменным, добавить метод заполнения графика
 */
class ChartData
{
    // массив временных меток, количество всегда должно совпадать с количеством значений
    public array $marks = [];
    // массив значений всегда хранится по уникальному имени
    public array $values = [];
    public array $additions = [];

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
}