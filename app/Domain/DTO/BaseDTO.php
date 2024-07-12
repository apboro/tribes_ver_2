<?php

declare(strict_types=1);

namespace App\Domain\DTO;

use App\Helper\Json;

class BaseDTO
{
    /**
     * @throws \JsonException
     */
    public function toArray(): array
    {
        return Json::toArray($this->toJson());
    }

    /**
     * @throws \JsonException
     */
    public function toJson(): string
    {
        return Json::make($this);
    }
}