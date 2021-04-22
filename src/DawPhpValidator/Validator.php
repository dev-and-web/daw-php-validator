<?php

namespace DawPhpValidator;

use DawPhpValidator\Contracts\ValidatorInterface;
use DawPhpValidator\Config\Lang;
use DawPhpValidator\Exception\ValidatorException;
use DawPhpValidator\Support\Request\Request;
use DawPhpValidator\Support\String\Str;

/**
 * Classe client.
 *
 * Pour vérifications des données.
 *
 * _Fonctionnement de ce package :
 * Pour générer les réponses,
 * la classe "Validator" fait appelle à la classe "Message"
 * qui fait appelle à un Renderer ("HtmlRenderer", ou "JsonRenderer")
 * et retournera la réponse (success ou erreur(s)).
 *
 * @link     https://github.com/dev-and-web/daw-php-validator
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class Validator implements ValidatorInterface
{
    /**
     * POST ou GET - Sera à POST par defaut
     *
     * @var mixed
     */
    private $requestMethod;

    /**
     * Eventuel(s) règle(s) da validation à ajouter
     *
     * @var array
     */
    private static array $extends = [];

    /**
     * Langue choisie dans config/config.php
     *
     * @var string
     */
    private static $langValidation;
    
    /**
     * Pour éventuellement personnaliser certains attributs de validation
     *
     * @var string
     */
    private string $label;

    /**
     * Name du input
     *
     * @var string
     */
    private string $input;

    /**
     * Valeur des rules qu'on passe à un input
     *
     * @var mixed
     */
    private $value;

    /**
     * Attributs de validation personnalisés
     *
     * @var array
     */
    private array $labels = [];

    /**
     * Contiendra les éventuels erreurs
     *
     * @var array
     */
    private array $errors = [];

    /**
     * @const string
     */
    const REGEX_TEL = '/^[0-9-+(),;._ \/]{4,20}$/';

    /**
     * @const string
     */
    const REGEX_SLUG = '/^[a-z0-9\-]+$/';

    /**
     * @const string
     */
    const REGEX_ALPHA = '/^[a-z]+$/i';

    /**
     * @const string
     */
    const REGEX_INTEGER = '/^[0-9]+$/';

    /**
     * @const string
     */
    const REGEX_ALPHA_NUMERIC = '/^[a-z0-9]+$/i';

    /**
     * @const string
     */
    const REGEX_DATE_TIME = '#^\d{2}/\d{2}/\d{4} \d{2}:\d{2}$#';

    /**
     * @const string
     */
    const REGEX_DATE = '#^\d{2}/\d{2}/\d{4}$#';

    /**
     * @const string
     */
    const REGEX_POSTALE_CODE = '/^[0-9]{5}$/';

    /**
     * @const string
     */
    const REGEX_CHARACTERS_PROHIBITED_NAME_FILE = '/[\/:*?"<>|\\\\ ÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØŒŠþÙÚÛÜÝŸàáâãäåæçèéêëìíîïðñòóôõöøœšÞùúûüýÿ¢ß¥£™©®ª×÷±²³¼½¾µ¿¶·¸º°¯§…¤¦≠¬ˆ¨‰]/';

    /**
     * Validator constructor.
     *
     * @param null|array $requestMethod
     */
    public function __construct($requestMethod = null)
    {
        $request = new Request();

        $this->requestMethod = ($requestMethod !== null) ? $requestMethod : $request->getPost()->all();

        self::setLangValidation();

        $this->labels = self::$langValidation['labels'];
    }

    /**
     * Pour ajouter une règle de validation
     *
     * @param string $rule
     * @param callable $callable
     * @param string $message
     * @throws ValidatorException
     */
    public static function extend(string $rule, callable $callable, string $message): void
    {
        self::setLangValidation();

        if (array_key_exists($rule, self::$extends)) {
            throw new ValidatorException('Rule "'.$rule.'" already exists.');
        }

        self::$extends[$rule]['bool'] = $callable;
        self::$extends[$rule]['message'] = $message;
    }

    /**
     * Charger la langue choisie dans config/config.php
     */
    private static function setLangValidation(): void
    {
        if (self::$langValidation === null) {
            self::$langValidation = Lang::getInstance()->validation();
        }
    }
    
    /**
     * Active le validateur
     *
     * @param array $inputsWithRules
     * @throws ValidatorException
     */
    public function rules(array $inputsWithRules): void
    {
        foreach ($inputsWithRules as $input => $rules) {
            $this->input = $input;

            if (is_array($rules)) {
                $this->setLabel($rules);
                
                foreach ($rules as $rule => $value) {
                    if ($rule != 'label') {
                        if ($rule == 'required' || isset($this->requestMethod[$this->input])) {
                            $this->value = $value;

                            $this->callRule($rule);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $rules
     */
    private function setLabel(array $rules): void
    {
        if (isset($rules['label'])) {
            $this->label = $rules['label'];
        } elseif (array_key_exists($this->input, $this->labels)) {
            $this->label = $this->labels[$this->input];
        } else {
            $this->label = ucfirst($this->input);
        }
    }

    /**
     * Appeler la règle de validation
     *
     * @param string $rule
     * @throws ValidatorException
     */
    private function callRule(string $rule): void
    {
        $methodVerify = 'verify'.Str::convertSnakeCaseToCamelCase($rule);

        if (method_exists($this, $methodVerify)) {
            $this->$methodVerify();
        } else {
            if (!array_key_exists($rule, self::$extends)) {
                throw new ValidatorException('Rule "'.$rule.'" not exist.');
            }

            $this->ruleWithExtend($rule);
        }
    }

    /**
     * @param string $rule
     */
    private function ruleWithExtend(string $rule): void
    {
        if (self::$extends[$rule]['bool']($this->input, $this->requestMethod[$this->input], $this->value) === false) {
            $this->errors[$this->input] = $this->label.': '.self::$extends[$rule]['message'];
        }
    }

    /**
     * Vérifier que valeur entrée dans le champ est bien alphabétique
     */
    private function verifyAlpha(): void
    {
        if ($this->value === true && !preg_match(self::REGEX_ALPHA, $this->requestMethod[$this->input])) {
            $this->errors[$this->input] = $this->pushError('alpha');
        }
    }

    /**
     * Vérifier que valeur entrée dans le champ est bien alphanumérique
     */
    private function verifyAlphaNumeric(): void
    {
        if ($this->value === true && !preg_match(self::REGEX_ALPHA_NUMERIC, $this->requestMethod[$this->input])) {
            $this->errors[$this->input] = $this->pushError('alpha_numeric');
        }
    }

    /**
     * Vérifier que valeur est entrée entre 2 valeurs spécifiées
     *
     * $this->value - (array numeroté). Valeur doit etre entre $this->value[0] (valeur min) et $this->value[1] (valeur max)
     */
    private function verifyBetween(): void
    {
        if ($this->requestMethod[$this->input] < $this->value[0] || $this->requestMethod[$this->input] > $this->value[1]) {
            $this->errors[$this->input] = $this->pushError('between', $this->value);
        }
    }

    /**
     * Pour obliger 2 valeurs à êtres égales
     */
    private function verifyConfirm(): void
    {
        if ($this->value[0] != $this->value[1]) {
            $this->errors[$this->input] = $this->pushError('confirm');
        }
    }

    /**
     * Champ doit obligatoirement rester vide
     */
    private function verifyEmpty(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            $this->errors[$this->input] = $this->pushError('empty');
        }
    }

    /**
     * Vérifier que valeur entrée est bien au format d'une date
     */
    private function verifyFormatDate(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !preg_match(self::REGEX_DATE, $this->requestMethod[$this->input])) {
                $this->errors[$this->input] = $this->pushError('format_date');
            }
        }
    }

    /**
     * Vérifier que valeur entrée est bien au format d'une date/heure
     */
    private function verifyFormatDateTime(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !preg_match(self::REGEX_DATE_TIME, $this->requestMethod[$this->input])) {
                $this->errors[$this->input] = $this->pushError('format_date_time');
            }
        }
    }

    /**
     * Vérifier que valeur entrée est bien au format d'un Email
     */
    private function verifyFormatEmail(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !filter_var($this->requestMethod[$this->input], FILTER_VALIDATE_EMAIL) == true) {
                $this->errors[$this->input] = $this->pushError('format_email');
            }
        }
    }

    /**
     * Verifier que valeur entrée est bien au format d'une adresse IP
     */
    private function verifyFormatIp(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !filter_var($this->requestMethod[$this->input], FILTER_VALIDATE_IP)) {
                $this->errors[$this->input] = $this->pushError('format_ip');
            }
        }
    }

    /**
     * Verifier que valeur entrée est bien au format d'un nom de fichier
     */
    private function verifyFormatNameFile(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && preg_match(self::REGEX_CHARACTERS_PROHIBITED_NAME_FILE, $this->requestMethod[$this->input])) {
                $this->errors[$this->input] = $this->pushError('format_name_file');
            }
        }
    }

    /**
     * Verifier que valeur entrée est bien au format d'un code postale
     */
    private function verifyFormatPostalCode(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !preg_match(self::REGEX_POSTALE_CODE, $this->requestMethod[$this->input])) {
                $this->errors[$this->input] = $this->pushError('format_postal_code');
            }
        }
    }

    /**
     * Vérifier que valeur entrée est bien au format d'un d'un slug
     */
    private function verifyFormatSlug(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !preg_match(self::REGEX_SLUG, $this->requestMethod[$this->input])) {
                $this->errors[$this->input] = $this->pushError('format_slug');
            }
        }
    }

    /**
     * Vérifier que valeur entrée est bien au format d'un numéro de téléphone
     */
    private function verifyFormatTel(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !preg_match(self::REGEX_TEL, $this->requestMethod[$this->input])) {
                $this->errors[$this->input] = $this->pushError('format_tel');
            }
        }
    }

    /**
     * Vérifier que valeur entrée est bien au format d'un d'une URL
     */
    private function verifyFormatUrl(): void
    {
        if ($this->requestMethod[$this->input] != '') {
            if ($this->value === true && !filter_var($this->requestMethod[$this->input], FILTER_VALIDATE_URL)) {
                $this->errors[$this->input] = $this->pushError('format_url');
            }
        }
    }

    /**
     * Vérifier que valeur entrée dans le champ est bien un entier
     */
    private function verifyInteger(): void
    {
        if ($this->value === true && !preg_match(self::REGEX_INTEGER, $this->requestMethod[$this->input])) {
            $this->errors[$this->input] = $this->pushError('integer');
        }
    }

    /**
     * Vérifier si donnée envoyés est dans un array
     */
    private function verifyInArray(): void
    {
        if (!in_array($this->requestMethod[$this->input], $this->value)) {
            $this->errors[$this->input] = $this->pushError('in_array');
        }
    }

    /**
     * Nombre de caractères maximum autorisés dans champ
     */
    private function verifyMax(): void
    {
        if (mb_strlen($this->requestMethod[$this->input]) > $this->value) {
            $this->errors[$this->input] = $this->pushError('max', $this->value);
        }
    }

    /**
     * Nombre de caractères minimum autorisés dans champ
     */
    private function verifyMin(): void
    {
        if (mb_strlen($this->requestMethod[$this->input]) < $this->value) {
            $this->errors[$this->input] = $this->pushError('min', $this->value);
        }
    }

    /**
     * Verifier que valeur entrée n'est pas au format d'un regex spécifique
     */
    private function verifyNoRegex(): void
    {
        if (preg_match($this->value, $this->requestMethod[$this->input])) {
            $this->errors[$this->input] = $this->pushError('no_regex', $this->value);
        }
    }

    /**
     * Vérifier si donnée envoyés n'est pas dans un array
     */
    private function verifyNotInArray(): void
    {
        if (in_array($this->requestMethod[$this->input], $this->value)) {
            $this->errors[$this->input] = $this->pushError('not_in_array');
        }
    }
    
    /**
     * Verifier que valeur entrée est bien au format d'un regex spécifique
     */
    private function verifyRegex(): void
    {
        if (!preg_match($this->value, $this->requestMethod[$this->input])) {
            $this->errors[$this->input] = $this->pushError('regex', $this->value);
        }
    }

    /**
     * Champ doit obligatoirement etre remplis
     */
    private function verifyRequired(): void
    {
        if (
            ($this->value === true && !array_key_exists($this->input, $this->requestMethod)) OR
            $this->requestMethod[$this->input] == '')
        {
            $this->errors[$this->input] = $this->pushError('required');
        }
    }

    /**
     * Si il y a une erreur -> pushera une erreur par input
     *
     * @param string $key - Key dans tableaux inclut dans resources/lang...
     * @param null|string|array $value - Pour éventuellemnt {value} dans tableaux inclut dans resources/lang...
     * @return string
     */
    private function pushError(string $key, $value = null): string
    {
        $errorMessage = str_replace('{field}', $this->label, self::$langValidation[$key]);

        if ($value !== null) {
            if (is_array($value)) {  // utile pour 'between'
                $i = 0;
                foreach ($value as $v_null) {
                    $errorMessage = str_replace('{value_'.$i.'}', $value[$i], $errorMessage);
                    $i++;
                }
            } else {
                $errorMessage = str_replace('{value}', $value, $errorMessage);
            }
        }

        return $errorMessage;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $input
     * @param string $error
     */
    public function addErrorWithInput(string $input, string $error): void
    {
        $this->errors[$input] = $error;
    }

    /**
     * Pour éventuellemnt ajouter des erreurs "à la volé" selon éventuels traitements
     *
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return bool - True si formulaire soumis est valide, false si pas valide
     */
    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Vérifier si un input à une erreur
     *
     * @param string $key - name de l'input
     * @return bool - True si input à au minimum une erreur
     */
    public function hasError(string $key): bool
    {
        return isset($this->errors[$key]);
    }

    /**
     * @param string $key - name de l'input
     * @return string - Erreur(s) de l'input
     */
    public function getError(string $key): string
    {
        return $this->hasError($key) ? $this->errors[$key] : '';
    }

    /**
     * @return array - Tableau associatif des erreurs
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string - Le message de confirmation
     */
    public function getSuccess(): string
    {
        return self::$langValidation['success_message'];
    }
    
    /**
     * @return Message
     */
    public function getMessages(): Message
    {
        return new Message($this);
    }

    /**
     * @return string - Les erreurs à afficher au format HTML
     */
    public function getErrorsHtml(): string
    {
        return $this->getMessages()->toHtml();
    }

    /**
     * @return string - Les erreurs à afficher au format Json
     */
    public function getErrorsJson(): string
    {
        return $this->getMessages()->toJson();
    }
}
