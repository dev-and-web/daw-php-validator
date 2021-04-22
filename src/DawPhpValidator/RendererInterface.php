<?php

namespace DawPhpValidator;

/**
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
Interface RendererInterface
{
    /**
     * @return string - Les erreurs à afficher
     */
    public function getErrors(): string;

    /**
     * @return string - Le message de confirmation
     */
    public function getSuccess(): string;
}
