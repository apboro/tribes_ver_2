<?php

namespace Tests;

use App\Services\TelegramComponents\FakeLogger;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
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
}