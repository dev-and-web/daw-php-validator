<?php

namespace DawPhpValidator\Support\String;

/**
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class Str
{
    /**
     * Cache de camel-cased words.
     *
     * @var array
     */
    private static array $camelCache = [];
    
    /**
     * Pour remplacer format snake_case par format camelCase
     *
     * @param string $value - camelCase
     * @return string
     */
    public static function convertSnakeCaseToCamelCase(string $value): string
    {
        if (isset(self::$camelCache[$value])) {
            return self::$camelCache[$value];
        }

        return self::$camelCache[$value] = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
    }
}
