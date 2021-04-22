<?php

namespace DawPhpValidator\Contracts\Config;

/**
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
Interface ConfigInterface
{
    /**
     * @param array $config
     */
    public static function set(array $config): void;

    /**
     * @return array|string
     */
    public static function get(string $param = null);
}
