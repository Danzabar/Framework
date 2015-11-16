<?php

namespace Wasp\Controller;

use Wasp\DI\DependencyInjectionAwareTrait;

/**
 * The Base Controller
 *
 * @package Wasp
 * @subpackage Controller
 * @author Dan Cox
 */
class BaseController
{
    use DependencyInjectionAwareTrait;

    /**
     * Entity
     *
     * @var String
     */
    protected $entity;

    /**
     * Magic getter for accessing services from the DI
     *
     * @param String $key
     * @return Object
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Gets a service from the DependencyInjector
     * This function exists for those services which have special characters and
     * cant be fetched via the magic method
     *
     * @param String $service
     * @return Object
     */
    public function get($service)
    {
        return $this->DI->get($service);
    }
} // END class BaseController
