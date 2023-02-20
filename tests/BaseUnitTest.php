<?php

namespace Tests;

use Illuminate\Support\Facades\Storage;

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