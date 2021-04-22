<?php

namespace Tests\DawPhpValidator\Config;

use PHPUnit\Framework\TestCase;
use DawPhpValidator\Config\Config;

class ConfigTest extends TestCase
{
    public function testConfig()
    {
        Config::set(['lang' => 'en']);

        $this->assertEquals('en', Config::get()['lang']);

        $this->assertTrue(is_array(Config::get()));
    }
}
