<?php

namespace Tests\DawPhpValidator\Support\String;

use PHPUnit\Framework\TestCase;
use DawPhpValidator\Support\String\Str;

class StrTest extends TestCase
{
    public function testStr()
    {
        $this->assertTrue(is_string(Str::convertSnakeCaseToCamelCase('rrrrfrf')));
    }
}
