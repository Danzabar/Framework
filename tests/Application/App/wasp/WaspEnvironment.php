<?php

namespace App\Wasp;

use Wasp\Environment\AbstractEnvironment;

/**
 * The environment controls how your application
 * is built
 */
class WaspEnvironment extends AbstractEnvironment
{

    public function config()
    {
        $profiles = require_once dirname(__DIR__) . '/profiles.php';

        $this->config->add('profiles',  $profiles);
        $this->config->add('config_dir', __DIR__ . '/src/Config/');
        $this->config->add('config_files', array(
            'application',
            'assets',
            'auth',
            'commands',
            'database',
            'extensions',
            'templates'
        ));
    }

    public function build()
    {
        // Build the Dependency Injector
        $this->buildDI();

        // Setup connections
        $this->setupConnections();

        // Connect to the default database connection
        $this->connect();

        // Start templating engine
        $this->setupTemplates( __DIR__ . '/src//Views/');
    }
}
