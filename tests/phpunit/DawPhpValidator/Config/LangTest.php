<?php

namespace Tests\DawPhpValidator\Config;

use PHPUnit\Framework\TestCase;
use DawPhpValidator\Config\Lang;

class LangTest extends TestCase
{
    public function testLang()
    {
        $this->assertTrue(is_array(Lang::getInstance()->validation()));
    }
}
