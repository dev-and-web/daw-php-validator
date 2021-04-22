# DAW PHP Validator - English documentation

[![Latest Stable Version](https://poser.pugx.org/dev-and-web/daw-php-validator/v/stable)](https://packagist.org/packages/dev-and-web/daw-php-validator)
[![License](https://poser.pugx.org/dev-and-web/daw-php-validator/license)](https://packagist.org/packages/dev-and-web/daw-php-validator)

DAW PHP Validator is a Open Source PHP library of simple validation system.

*Verify easily the submitted data!*
```php
<?php

if (Request::isPost()) {
    $validator = new Validator();

    $validator->rules($arrayRules);

    if ($validator->isValid()) {
        // Response with success
    }
}
```




### Requirements

* PHP >= 7.4






## * Summary *

* Introduction
* Installation
* Available Validation Rules
* Validator methods
* How to do?
* Example
* Description of the "rules" method
* Add custom validation rules
* To add an error on the fly according to a treatment
* Verify if a specific error exists
* Responses
* Set default configuration
* Contributing






## Introduction

This Open Source library allows you to validate your forms.
The objective is to verify the submitted data, which is essential for the security of your web applications.






## Installation

Installation via Composer:
```
php composer.phar require dev-and-web/daw-php-validator 2.0.*
```


If you do not use Composer to install this package,
you will have to manually "require" before using this package.
Example:
```php
<?php

require_once 'your-path/daw-php-validator/src/DawPhpValidator/bootstrap/load.php';
```






## Methods

| Static ? Return type | Name | Description |
| ------- | -------------- | ----------- |
| static void | extend(string $rule, callable $callable, string $message) | To add a validation rule |
| void | __construct($requestMethod = null) | Constructor |
| void | rules(array $inputsWithRules) | Activate the validator |
| bool | isValid() | True if submitted form is valid |
| string | getErrors() | Associative array of errors |
| string | getErrorsHtml() | Errors to display in HTML format |
| string | getErrorsJson() | Errors to display in JSON format |
| bool | hasError('input_name') | True if an input to an error |
| string | getError('input_name') | Error(s) of the input |






## Available Validation Rules

* 'alpha' => bool
* 'alpha_numeric' => bool
* 'between' => array (2 values: min value, max value)
* 'confirm' => array (2 values to verify)
* 'empty' => bool
* 'format_date' => bool
* 'format_date_time' => bool
* 'format_email' => bool
* 'format_ip' => bool
* 'format_name_file'
* 'format_postal_code' => bool
* 'format_slug' => bool
* 'format_tel' =>bool
* 'format_url' => bool
* 'integer' => bool
* 'in_array' => array
* 'max' => int
* 'min' => int
* 'no_regex'=> string (regex which must not match)
* 'not_in_array' => array
* 'regex'=> string (regex must match)
* 'required' => bool

For the "format_*", the format will only check if a value is submitted.
So think of adding to the rules of the field a 'required' if you want to ban the empty value.






## How to do?

* You must first instantiate the Validator.
* Then you pass him validation rules.
* Then check whether the submitted form is valid according to the rules defined.
* The response can then be returned in HTML or Json format.






## Example

### PHP Code (with for example all validation rules available in my method "rules")

```php
<?php

use DawPhpValidator\Validator;

if (Request::isPost()) {
    // Instantiate the Validator
    $validator = new Validator();

    //$validator = new Validator($_GET);  // For method GET instead of POST

    // Add validation rule(s) for inputs
    $validator->rules([
        'alpha' => [
             'alpha' => true
        ],
        'alpha_numeric' => [
            'label' => 'Alpha numeric',
            'alpha_numeric' => true
        ],
        'between' => [
            'between' => [2, 9]
        ],
        'password' => [
            'confirm' => [$_POST['password'], $_POST['password_confirmation']]
        ],
        'empty' => [
            'empty' => true
        ],
        'date' => [
            'format_date' => true,
        ],
        'date_time' => [
            'format_date_time' => true,
        ],
        'email' => [
            'format_email' => true,
        ],
        'ip' => [
            'format_ip' => true,
        ],
        'name_file' => [
            'label' => 'File name',
            'format_name_file' => true
        ],
        'postal_code' => [
            'format_postal_code' => true
        ],
        'slug' => [
            'format_slug' => true,     
        ],
        'tel' => [
            'format_tel' => true,     
        ],
        'url' => [
            'format_url' => true,     
        ],
        'integer' => [
            'integer' => true,     
        ],
        'in_array' => [
            'in_array' => [1, 2, 3],     
        ],
        'max' => [
            'max' => 10,     
        ],
        'min' => [
            'min' => 5,     
        ],
        'no_regex' => [
            'no_regex' => "#^[0-9]+$#",    
        ],
        'regex' => [
            'regex' => "#^[a-z]+$#",    
        ],
        'required' => [
            'required' => true,     
        ],
    ]);
    
    // Verify if the form is valid
    if ($validator->isValid()) {
        // Success
    } else {
        // Show all errors:
        var_dump($validator->getErrors());    // return array
        var_dump($validator->getErrorsHtml());    // return string
        var_dump($validator->getErrorsJson());    // return string
    }
}
```




### HTML Code (example of form to test the code PHP of above)

```html

<form action="#" method="post">
    <label>Alpha:</label>
    <input type="text" name="alpha"><br>

    <label>Alpha numeriqc:</label>
    <input type="text" name="alpha_numeric"><br>

    <label>Between:</label>
    <input type="text" name="between"><br>

    <label>Password:</label>
    <input type="password" name="password"><br>

    <label>Confirmamtion of Password:</label>
    <input type="password_confirmation" name="password_confirmation"><br>

    <label>Empty:</label>
    <input type="empty" name="empty"><br>

    <label>Date:</label>
    <input type="date" name="date"><br>

    <label>Date time:</label>
    <input type="date_time" name="date_time"><br>

    <label>Email:</label>
    <input type="email" name="email"><br>

    <label>IP:</label>
    <input type="ip" name="ip"><br>

    <label>File name:</label>
    <input type="name_file" name="name_file"><br>

    <label>Postal code:</label>
    <input type="postal_code" name="postal_code"><br>

    <label>Slug:</label>
    <input type="text" name="slug"><br>

    <label>TEL:</label>
    <input type="text" name="tel"><br>

    <label>URL:</label>
    <input type="text" name="url"><br>

    <label>Integer:</label>
    <input type="text" name="integer"><br>

    <label>In array:</label>
    <select name="in_array">
        <option value="0">Choisir...</option>
        <option value="1">Choix 1</option>
        <option value="2">Choix 2</option>
        <option value="3">Choix 3</option>
    </select><br>

    <label>Max:</label>
    <input type="text" name="max"><br>

    <label>Min:</label>
    <input type="text" name="min"><br>

    <label>No regex:</label>
    <input type="text" name="no_regex"><br>

    <label>Regex:</label>
    <input type="text" name="regex"><br>

    <label>Required:</label>
    <input type="text" name="required"><br>

    <input type="submit" value="Envoyer">
</form>
```






## Description of the "rules" method

In parameter of the method "rules" one must pass an associative array.
The keys (string) must be the name of the inputs, the values (array) must be the rules that are specified to this input.
```php
<?php

use DawPhpValidator\Validator;

if (Request::isPost()) {
    $validator = new Validator();

    $validator->rules([
        'input_name_1' => $arrayRulesInput1,
        'input_name_2' => $arrayRulesInput2,
    ]);

    var_dump($validator->isValid());    // return bool
}
```






## Add custom validation rules

If you want to add a validation rule, you must use the "extend" method before the "rules" method.
Example:

```php
<?php

use DawPhpValidator\Validator;

// Add a new validation rule
// $input will be the name of the input
// $value will be the submitted value of the input
// $parameters will be its specified value to the 'rule_name' validation rule to a 'name_input' in the rules method
Validator::extend('rule_name', function ($input, $value, $parameters) {
    return $value == $parameters;    // Return your condition for return a bool
}, 'Your custom error message.');
    
if (Request::isPost()) {
    $validator = new Validator();
    
    // Add validation rule(s) for inputs
    $validator->rules([
        // Add the validation rule you just created for the input
        'input_name' => ['rule_name'=>'value_to_rules'],
    ]);
}
```






## To add an error on the fly according to a treatment

```php
<?php

if ($condition === false) {
    $validator->addError('Your custom error message...');
}
```






## Verify if a specific error exists

```php
<?php

if ($validator->hasError('input_name')) {
    var_dump($validator->getError('input_name'));    // return string
}
```





## Responses

Here are the available methods to obtain the answers:
```php
<?php

if ($validator->isValid()) {
    // Methods only if the form is valid:

    echo $validator->getSuccess();
} else {
    // Methods available only if the form is not valid:

    var_dump($validator->getErrors());    // return array

    echo $validator->getErrorsHtml();

    echo $validator->getErrorsJson();
}

// Available methods no matter if the form is valid or not:

echo $validator->getMessages()->toHtml();
// return to the same as:
echo $validator->getMessages();

echo $validator->getMessages()->toJson();
```






## Set default configuration

```php
<?php

use DawPhpValidator\Config\Config;

// Change the language - Is 'fr' by default. Supported: 'fr', 'en'
Config::set(['lang' => 'en']);
```






## Contributing

### Bugs and security Vulnerabilities

If you discover a bug or a security vulnerability within DAW PHP Validator, please send a message to Stephen. Thank you.
All bugs and all security vulnerabilities will be promptly addressed.




### License

The DAW PHP Validator is Open Source software licensed under the MIT license.
