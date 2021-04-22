<?php

namespace DawPhpValidator;

use DawPhpValidator\Contracts\ValidatorInterface;

/**
 * Pour retourner les messages du Validator
 *
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class Message
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Message constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * @return string - La réponse au format HTML
     */
    public function toHtml(): string
    {
        $htmlRenderer = new HtmlRenderer($this->validator);

        if ($this->validator->isValid()) {
            return $htmlRenderer->getSuccess();
        }

        return $htmlRenderer->getErrors();
    }

    /**
     * @return string - La réponse au format JSON
     */
    public function toJson(): string
    {
        $jsonRenderer = new JsonRenderer($this->validator);

        if ($this->validator->isValid()) {
            return $jsonRenderer->getSuccess();
        }

        return $jsonRenderer->getErrors();
    }
}
