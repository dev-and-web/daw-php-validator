<?php

namespace Tests\DawPhpValidator\Support\String;

use PHPUnit\Framework\TestCase;
use DawPhpValidator\Support\String\Json;

class JsonTest extends TestCase
{
    public function testEncode()
    {
        $this->assertTrue(is_string(Json::encode('ef1fe')));
    }
}
