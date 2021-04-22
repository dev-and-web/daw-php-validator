# DAW PHP Validator - Library - Form validation

[![Latest Stable Version](https://poser.pugx.org/dev-and-web/daw-php-validator/v/stable)](https://packagist.org/packages/dev-and-web/daw-php-validator)
[![License](https://poser.pugx.org/dev-and-web/daw-php-validator/license)](https://packagist.org/packages/dev-and-web/daw-php-validator)

DAW PHP Validator is a Open Source PHP library of simple validation system.

*Verify easily the submitted data!*




### Author

Package developed by:
[Développeur web Freelance](https://www.devandweb.fr)
[![Developpeur PHP Freelance](https://www.devandweb.fr/medias/app/website/developpeur-web.png)](https://www.devandweb.fr/freelance/developpeur-php)




### Simple example

```php
<?php

if ($request->isPost()) {
    $validator = new Validator();

    $validator->rules($arrayRules);

    if ($validator->isValid()) {
        // Response with success
        return $validator->getMessages()->toHtml();
    } else {
        return $validator->getErrorsHtml();
    }
}
```

Add custom validation rules:
```php
<?php

Validator::extend('rule_name', function ($input, $value, $parameters) {
    return $value == $parameters;
}, 'Your custom error message.');
```

You also have many other features.




### Requirements

* PHP >= 7.4




### Documentation

* The documentation is in folder "docs" of this package:

[English](https://github.com/dev-and-web/daw-php-validator/tree/2.0/docs/en/doc.md)
|
[French](https://github.com/dev-and-web/daw-php-validator/tree/2.0/docs/fr/doc.md)




## Installation

Installation via Composer:
```
composer require dev-and-web/daw-php-validator
```






## Contributing

### Bugs and security Vulnerabilities

If you discover a bug or a security vulnerability within DAW PHP Validator, please send a message to Stephen. Thank you.
All bugs and all security vulnerabilities will be promptly addressed.




### License

The DAW PHP Validator is Open Source software licensed under the MIT license.
