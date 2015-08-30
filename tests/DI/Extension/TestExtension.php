<?php namespace Wasp\Test\DI\Extension;

use Wasp\DI\Extension;

/**
 * A extension for testing
 *
 * @package Wasp
 * @subpackage Tests\Extensions
 * @author Dan Cox
 */
class TestExtension extends Extension
{

    /**
     * the alias
     *
     * @var string
     */
    protected $alias = 'test';

    /**
     * Directory to find the files
     *
     * @var string
     */
    protected $directory;   

    /**
     * Set up extension details
     *
     * @return void
     * @author Dan Cox
     */
    public function setup()
    {
        $this->directory = __DIR__;
    }

    /**
     * The main extension function
     *
     * @return void
     * @author Dan Cox
     */
    public function extension()
    {
        $this->loader->load('extension.yml');
    }


} // END class TestExtension extends Extension
