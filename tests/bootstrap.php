<?php

/**
 * The Test bootstrap file
 *
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * For the Dependency Injection Tests, We need the two test classes
 *
 */
require_once __DIR__ . '/Services/Service.php';
require_once __DIR__ . '/Services/Library.php';

/**
 * Some Directory Constants to help with testing
 *
 */
define('SRC', dirname(__DIR__) . '/src/');
define('APPLICATION', SRC . 'Application/');
define('CONFIG', SRC.'Config/');
