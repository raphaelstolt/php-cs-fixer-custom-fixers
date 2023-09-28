# PHP-CS-Fixer custom fixers

![Test Status](https://github.com/raphaelstolt/php-cs-fixer-custom-fixers/workflows/test/badge.svg)
[![Version](http://img.shields.io/packagist/v/stolt/php-cs-fixer-custom-fixers.svg?style=flat)](https://packagist.org/packages/stolt/lean-package-validator)
![!PHP Version](https://img.shields.io/badge/php-8.0+-ff69b4.svg)
[![Software License](https://img.shields.io/badge/license-MIT-purple.svg?style=flat)](LICENSE.md)

A growing set of custom fixers for [PHP-CS-Fixer](https://cs.symfony.com/).

## Installation

The custom fixers should be installed through Composer.

```sh
composer require --dev stolt/php-cs-fixer-custom-fixers
```

Then in your PHP-CS-Fixer configuration file i.e. `.php-cs-fixer.php` register the custom 
fixer and use it as shown next.

```php
<?php
declare(strict_types=1);

return (new \PhpCsFixer\Config())
    ->registerCustomFixers(new \Stolt\PhpCsFixer\Fixers())
    ->setRules([
        'Stolt/namespace_prefix_internal_php_functions' => true,
    ]);
```
