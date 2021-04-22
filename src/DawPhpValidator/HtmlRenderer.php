<?php

namespace DawPhpValidator;

use DawPhpValidator\Contracts\ValidatorInterface;

/**
 * Pour retourner des string au format HTML
 *
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class HtmlRenderer implements RendererInterface
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    
	/**
	 * HtmlRender constructor.
     *
     * @param ValidatorInterface $validator
     */
	public function __construct(ValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

    /**
     * @return string - Les erreurs à afficher
     */
    public function getErrors(): string
    {
        $html = '';

        if (!$this->validator->isValid()) {
            $html .= '<ul>';
            foreach ($this->validator->getErrors() as $error)  {
                $html .= '<li>'.$error.'</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * @return string - Le message de confirmation
     */
    public function getSuccess(): string
    {
        $html = '';

        if ($this->validator->isValid()) {
            $html .= '<ul>';
            $html .= '<li>'.$this->validator->getSuccess().'</li>';
            $html .= '</ul>';
        }

        return $html;
    }
}
