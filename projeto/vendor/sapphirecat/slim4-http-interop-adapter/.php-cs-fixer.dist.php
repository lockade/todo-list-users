<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->append([__FILE__]);

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder);
