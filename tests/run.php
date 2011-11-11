#!/usr/bin/env php
<?php

$include_paths = array(
    __DIR__ . '/../vendor/phpunit',
    __DIR__ . '/../vendor/dbunit',
    __DIR__ . '/../vendor/php-file-iterator',
    __DIR__ . '/../vendor/php-text-template',
    __DIR__ . '/../vendor/php-token-stream',
    __DIR__ . '/../vendor/phpunit-mock-objects',
    __DIR__ . '/../vendor/phpunit-story',
    __DIR__ . '/../vendor/php-code-coverage',
    __DIR__ . '/../vendor/php-invoker',
    __DIR__ . '/../vendor/php-timer',
    __DIR__ . '/../vendor/php-selenium',
    get_include_path(),
);

set_include_path(implode(':', $include_paths));

require_once __DIR__ . '/../src/Selfish/Loader.php';
Selfish\Loader::register();

require 'phpunit.php';

