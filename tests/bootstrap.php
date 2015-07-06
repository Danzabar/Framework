<?php

/**
 * The Test bootstrap file
 *
 */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

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
define('ENTITIES', __DIR__ . '/Entity/Entities/');
define('MODULES', __DIR__ . '/Modules/');
define('TEMPLATES', __DIR__ . '/Templating/Templates/');
