<?php

use App\Wasp\WaspEnvironment;

/**
 *  Bootstrapping your app!
 *
 */
$autoloader = require_once '[baseDir]/vendor/autoload.php';

/**
 *  Build the Environment
 *
 */
$environment = new WaspEnvironment();
$environment->build();

/**
 *  Loading routes
 *
 */
$route = $environment->getDI()->get('route');
require_once dirname(__DIR__) . '/Routes.php';

/**
 *  Doctrine Annotation engine configuration
 *
 */
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($autoloader, 'loadClass'));

return $environment;
