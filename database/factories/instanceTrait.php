<?php

namespace Database\Factories;

use Exception;

trait instanceTrait
{
    public function createItem($attributes = [])
    {
        $this->checkValue();
        $attributes = array_merge($this->definition(),$attributes);
        $result = parent::create($attributes);
        self::$items[] = $result;
        return $result;
    }

    /**
     * @param array $attributes need [$attribute=>$value]
     * @return mixed|null
     * @throws Exception
     */
    public function getItemByAttrs(array $attributes)
    {
        $this->checkValue();

        foreach(self::$items as $item) {
            foreach($attributes as $attribute => $value) {
                if($item[$attribute] !== $value) continue(2);
            }
            return $item;
        }

        return null;
    }

    protected function checkValue()
    {
        if(!isset(self::$items)) {
            throw new Exception('Не определено статическое свойство $items в классе '.self::class);
        }
    }

    /**
     * для очистки состояния при использовании в тестах
     * @return void
     * @throws Exception
     */
    public function clearInstance()
    {
        $this->checkValue();
        self::$items = [];
    }
}