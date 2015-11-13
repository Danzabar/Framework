<?php

/**
 * This is where your application settings should go.
 *
 */
return array(

    // Basic App Settings
    'name'                              => 'Wasp',
    'cli_name'                          => 'Wasp CLI',
    'version'                           => 'v1.0.0',

    // Database Settings
    'default_connection'                => 'default',

    // Debug
    'debug'                             => true,

    // Dependency Injection Cache
    'di_cache_namespace'                => 'Wasp\Cache',
    'di_cache_directory'                => '/var/www/Framework/tests/Application/App/wasp/cache/AppCache.php',

    // Datetime settings
    'timezone'                          => 'GMT',
);
