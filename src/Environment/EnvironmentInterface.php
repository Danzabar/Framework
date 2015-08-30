<?php

namespace Wasp\Environment;

use Wasp\Application\Application;

/**
 * The Environment Interface
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
interface EnvironmentInterface
{

    /**
     * Loads the Application into the Environment
     *
     * @return Mixed
     * @author Dan Cox
     */
    public function load(Application $app);

    /**
     * Setup the Environment options like DI and Settings.
     *
     * @return void
     * @author Dan Cox
     */
    public function setup();
}
