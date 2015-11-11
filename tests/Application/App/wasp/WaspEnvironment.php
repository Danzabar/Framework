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
        $this->config->add('profiles', '/var/www/Framework/tests/Application/App/profiles.php');
        $this->config->add('config_files', array(
            'application',
            'assets',
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

        // Connect to the default database connection
        $this->connect();

        // Start templating engine
        $this->setupTemplates('/var/www/Framework/tests/Application/App/wasp/Views/');
    }
}
