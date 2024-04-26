<?php

declare(strict_types=1);

namespace App\Bundle\Telegram\MiniApp\Validator;

use App\Bundle\Telegram\Exceptions\ValidationException;
use App\Bundle\Telegram\MiniApp\InitDataDTO;
use Illuminate\Support\Facades\Log;

final class InitDataValidator extends Validator
{
    public function validate(string $data): InitDataDTO
    {
        $rawData = $this->parse($data);
        $initData = new InitDataDTO($rawData);

        $rawData = $this->prepare($rawData);
        $rawData = $this->sort($rawData);
        $rawData = $this->ridHash($rawData);
        $data    = $this->implode($rawData);
        $hash    = $this->hashInitData($data, $this->token);

        if (!$this->matchHash($hash, $initData->hash ?? '')) {
            log::info('TMP ValidationException');
            throw new ValidationException();
        }

        return $initData;
    }

    /**
     * @return array<string, int|string|bool>
     */
    public function parse(string $data): array
    {
        parse_str($data, $result);

        /** @var array<string, int|string|bool> $result */
        return $result = array_filter($result , 'is_string', ARRAY_FILTER_USE_KEY);
    }
}