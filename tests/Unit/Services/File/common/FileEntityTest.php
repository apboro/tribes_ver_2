<?php

namespace Tests\Unit\Services\File\common;

use App\Services\File\common\FileEntity;
use PHPUnit\Framework\TestCase;

class FileEntityTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetEntitySuccess()
    {
        $serviice = new FileEntity();
        $rezult = $serviice->getEntity([]);
        $this->assertTrue($rezult);
    }

    public function testGetEntityWrong()
    {
        $this->assertTrue(true);
    }


}
