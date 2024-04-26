<?php


declare(strict_types=1);


namespace App\Bundle\Telegram\MiniApp\InitData;

use App\Bundle\Telegram\MiniApp\Support\Arrayable;
use Carbon\Carbon;
use ReflectionNamedType;
use ReflectionProperty;

abstract class Make extends Arrayable
{
    /**
     * @param array $props
     */
    public function __construct(array $props = [])
    {
        foreach ($props as $prop => $value) {
            if ($prop === 'auth_date') {
                $value = Carbon::createFromTimestamp((int)$value);
            }

            $this->setProperty($this->camelize($prop), $value);
        }
    }

    /**
     * @throws \ReflectionException
     */
    protected function setProperty(string $property, $value): void
    {
        if(!property_exists(get_class($this), $property)) {
            return;
        }

        $reflection = new ReflectionProperty(get_class($this), $property);

        $type = $reflection->getType();

        if(!($type instanceof ReflectionNamedType) || $type->isBuiltin()) {
            $this->$property = $value;
            return;
        }

        $class = $type->getName();

        $this->$property = is_subclass_of($class, self::class)
            ? new $class($this->tryParseJSON($value))
            : $value;
    }

    /**
     * @return array<string, int|string|bool>
     */
    protected function tryParseJSON($data): array
    {
        if(is_string($data)) {
            /** @var array<string, int|string|bool> $assoc */
            $assoc = json_decode($data, true);
            return $assoc;
        }
        return [];
    }

    private function camelize(string $str): string
    {
        return lcfirst(str_replace('_', '', mb_convert_case($str, MB_CASE_TITLE, 'UTF-8')));
    }
}