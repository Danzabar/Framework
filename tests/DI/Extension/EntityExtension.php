<?php

namespace Wasp\Test\DI\Extension;

use Wasp\DI\Extension;

/**
 * Extension class for entities
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class EntityExtension extends Extension
{
    /**
     * The extension alias
     *
     * @var String
     */
    protected $alias = 'entity';

    /**
     * The directory which holds the config
     *
     * @var String
     */
    protected $directory;

    /**
     * Set up extension
     *
     * @return void
     * @author Dan Cox
     */
    public function setup()
    {
        $this->directory = __DIR__;
    }

    /**
     * The extension method
     *
     * @return void
     * @author Dan Cox
     */
    public function extension()
    {
        $this->loader->load('entities.yml');
    }

} // END class EntityExtension extends Extension
