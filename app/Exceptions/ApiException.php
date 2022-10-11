<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ApiException extends Exception
{
    use PrettyArrayToString;

    public $status = 499;
    private array $context;

    public function __construct($message = "", $context = [])
    {
        parent::__construct($message, $this->status, null);
        $this->context = $context;
    }

    public function report()
    {
        Log::error($this->message,$this->context);
    }

    public function getContext()
    {
        return $this->context;
    }
}