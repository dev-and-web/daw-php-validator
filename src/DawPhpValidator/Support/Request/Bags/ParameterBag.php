<?php

namespace DawPhpValidator\Support\Request\Bags;

/**
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class ParameterBag
{
    /**
     * Parameter storage.
     *
     * @var array
     */
    private array $parameters;

    /**
     * ParameterBag Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array - Les paramètres
     */
    public function all(): array
    {
        return $this->parameters;
    }
}
