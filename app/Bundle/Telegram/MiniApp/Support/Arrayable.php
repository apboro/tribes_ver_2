<?php

declare(strict_types=1);

namespace App\Bundle\Telegram\MiniApp\Support;

use ReflectionClass;
use ReflectionProperty;

abstract class Arrayable
{
    /**
     * Converts the object or its properties to an array recursively.
     *
     * @param ?object $object The object to convert to an array. If null, the current object is used.
     * @return array<string, mixed> The array representation of the object or its properties.
     */
    public function toArray(?object $object = null): array
    {
        $object = $object ?? $this;

        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];

        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $object->$name ?? null;
            $result[$name] = $value instanceof self
                ? $this->toArray($value)
                : $value;
        }

        return $result;
    }
}