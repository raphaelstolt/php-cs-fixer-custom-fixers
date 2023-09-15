<?php
declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__);

$rules = [
    'psr_autoloading' => false,
    '@PSR12' => true,
    '@PHP80Migration' => true,
    'phpdoc_order' => true,
    'ordered_imports' => true,
];

$cacheDir = getenv('HOME') ? getenv('HOME') : __DIR__;

$config = new Config();

return $config->setRules($rules)
    ->setFinder($finder)
    ->setCacheFile($cacheDir . '/.php-cs-fixer.cache');
