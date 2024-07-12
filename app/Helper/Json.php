<?php

declare(strict_types=1);

namespace App\Helper;

class Json
{
    /**
     * @throws \JsonException
     */
    public static function toArray(string $json)
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    public static function make($mixed): string
    {
       return json_encode($mixed, JSON_THROW_ON_ERROR);
    }
}