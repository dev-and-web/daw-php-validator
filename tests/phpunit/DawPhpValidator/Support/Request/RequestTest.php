<?php

namespace Tests\DawPhpValidator\Support\Request;

use PHPUnit\Framework\TestCase;
use DawPhpValidator\Support\Request\Request;

class RequestTest extends TestCase
{
    public function testRequest()
    {
        $request = new Request();

        $this->assertTrue(is_array($request->getPost()->all()));
    }
}
