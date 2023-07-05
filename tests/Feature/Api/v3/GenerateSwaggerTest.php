<?php

namespace Tests\Feature\Api\v3;

use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

class GenerateSwaggerTest extends TestCase
{

    public function testGenerateSwaggerDocs()
    {
        $output = $this->artisan('l5-swagger:generate');

        $output->assertSuccessful();
    }
}
