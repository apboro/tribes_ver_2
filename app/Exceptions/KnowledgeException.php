<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KnowledgeException extends Exception
{
    public $status = 423;
    private array $context;

    public function __construct($message = "", $context = [])
    {
        parent::__construct($message, $this->status, null);
        $this->context = $context;
    }

    public function report()
    {
        Log::channel('stack')->debug($this->message,$this->context);
    }

    public function getContext()
    {
       return $this->context;
    }
}