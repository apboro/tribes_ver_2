<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use UnexpectedValueException;

class Invalid
{
    /**
     * Build Exception
     *
     * @param string $message
     *
     * @return void
     */
    public static function run(string $message): void
    {
        $message = 'Invalid Argument: ' . $message;
        Log::error($message);

        throw new InvalidArgumentException($message);
    }

    /**
     * Build Exception
     *
     * @param string $message
     *
     * @return void
     *
     * @throw UnexpectedValueException
     */
    public static function NullException(string $message = ''): void
    {
        $message = 'Null Exception ' . $message;
        Log::error($message);

        throw new UnexpectedValueException($message);
    }

    public static function NotFound(string $message = ''): void
    {
        $message = ' Not Found Model ' . $message;
        Log::error($message);

        throw new UnexpectedValueException($message);
    }
}
