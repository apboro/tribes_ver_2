<?php

namespace Tests;

use App\Services\TelegramComponents\FakeLogger;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\TestHandler;
use Tests\TestCase;

class BaseUnitTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getDataFromFile($name = '', $asJson = false)
    {
        $jsonData = Storage::disk('test_data')->get("unit/$name");
        if ($asJson) {
            return $jsonData;
        }
        return json_decode($jsonData, true) ?: [];
    }
}