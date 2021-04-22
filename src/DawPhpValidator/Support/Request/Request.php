<?php

namespace DawPhpValidator\Support\Request;

use DawPhpValidator\Support\Request\Bags\ParameterBag;

/**
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class Request
{
    /**
     * @var ParameterBag - $_POST
     */
    private ParameterBag $post;

    /**
     * Request Constructor.
     */
    public function __construct()
    {
        $this->post = new ParameterBag($_POST);
    }

    /**
     * @return ParameterBag
     */
    public function getPost()
    {
        return $this->post;
    }
}
