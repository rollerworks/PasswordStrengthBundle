<?php

$header = <<<EOF
This file is part of the RollerworksPasswordStrengthBundle package.

(c) Sebastiaan Stok <s.stok@rollerscapes.net>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

/** @var \Symfony\Component\Finder\Finder $finder */
$finder = PhpCsFixer\Finder::create();
$finder
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules(
        array_merge(
            require __DIR__ . '/vendor/rollerscapes/standards/php-cs-fixer-rules.php',
            ['header_comment' => ['header' => $header]])
    )
    ->setFinder($finder);

return $config;
