<?php


declare(strict_types=1);


namespace App\Bundle\Telegram\MiniApp\Validator;

abstract class Validator
{
    protected $token;
    protected $throw;

    public function __construct(bool $throw = false)
    {
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->throw = $throw;
    }

    /**
     * @param array $data
     * @return array<int, string>
     */
    public function prepare(array $data): array
    {
        return array_map(
            fn($value, $key)  => $key . '=' . $value,
            $data,
            array_keys($data)
        );
    }

    /**
     * @param array $data
     * @return array<int, string>
     */
    public function ridHash(array $data): array
    {
        if ($withoutHash = preg_grep('/^hash=/i', $data, PREG_GREP_INVERT)) {
            return array_values($withoutHash);
        }

        return $data;
    }

    /**
     * @param array<int, string> $data
     */
    public function implode(array $data): string
    {
        return implode("\n", $data);
    }

    /**
     * @param array<int, string> $data
     * @return array<int, string>
     */
    public function sort(array $data): array
    {
        sort($data);
        return $data;
    }

    public function matchHash(string $hash1, string $hash2): bool
    {
        return 0 === strcmp($hash1, $hash2);
    }

    protected  function hashInitData(string $data, string $token): string
    {
        $secretKey = hash_hmac('sha256', $token, 'WebAppData', true);
        return bin2hex(hash_hmac('sha256', $data, $secretKey, true));
    }
}