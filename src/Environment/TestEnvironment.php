<?php

namespace Wasp\Environment;

use Wasp\Environment\AbstractEnvironment;
use Wasp\DI\Pass\SessionFilePass;
use Wasp\DI\Pass\MockeryPass;

/**
 * The test environment class
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
class TestEnvironment extends AbstractEnvironment
{

    /**
     * Configures folders and documents
     *
     * @return void
     */
    public function config()
    {
        // Nothing to do here
    }

    /**
     * Build Application
     *
     * @return void
     */
    public function build()
    {
        $this->DI->addCompilerPass(new MockeryPass);
        $this->DI->addCompilerPass(new SessionFilePass);

        $this->buildDI();
    }

} // END class TestEnvironment extends AbstractEnvironment
