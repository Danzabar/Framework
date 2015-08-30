<?php

namespace Wasp\DI;

use Wasp\DI\ServiceMockeryLibrary;
use Wasp\DI\ServiceMockeryDecorator;

/**
 * Service mockery class is used to create mockery instances of the DI services
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class ServiceMockery
{
    /**
     * The name of the service
     *
     * @var string
     */
    protected $serviceName;

    /**
     * Instance of the Service Mockery Library
     *
     * @var Object
     */
    protected $library;

    /**
     * Set up the Mockery instance
     *
     * @return void
     * @author Dan Cox
     */
    public function __construct($name)
    {
        $this->serviceName = $name;
        $this->library = new ServiceMockeryLibrary;
    }

    /**
     * Returns this instance of the Library
     *
     * @return ServiceMockLibrary
     * @author Dan Cox
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * Returns a Service Mockery decorator instance for this
     *
     * @return ServiceMockeryDecorator
     * @author Dan Cox
     */
    public function getMock()
    {
        return new ServiceMockeryDecorator($this->serviceName);
    }

    /**
     * Adds the Mockery instance to the library
     *
     * @return ServiceMockery
     * @author Dan Cox
     */
    public function add()
    {
        $this->library->add($this->serviceName);
        return $this;
    }

    /**
     * Remove from the library
     *
     * @return ServiceMockery
     * @author Dan Cox
     */
    public function remove()
    {
        $this->library->remove($this->serviceName);
        return $this;
    }

} // END class ServiceMockery
