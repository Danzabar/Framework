<?php

namespace Wasp\DI;

/**
 * A storage for the ServiceMockery classes
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class ServiceMockeryLibrary
{

    /**
     * An Array of Services
     *
     * @var Array
     */
    protected static $services = array();

    /**
     * Adds an item to the library
     *
     * @param String $name -  The name of the service
     * @return ServiceMockeryLibrary
     */
    public function add($name)
    {
        static::$services[$name] = $name;
        return $this;
    }

    /**
     * Finds a service by Name
     *
     * @param String $name - The name of the service
     * @return String|NULL
     */
    public function find($name)
    {
        if (array_key_exists($name, static::$services)) {
            return static::$services[$name];
        }

        return null;
    }

    /**
     * Remove a service by Name
     *
     * @param String $name - The name of the service
     * @return ServiceMockeryLibrary
     */
    public function remove($name)
    {
        if (array_key_exists($name, static::$services)) {
            unset(static::$services[$name]);
        }

        return $this;
    }

    /**
     * Returns All definitions
     *
     * @return Array
     */
    public function all()
    {
        return static::$services;
    }

    /**
     * Removes all definitions
     *
     * @return ServiceMockeryLibrary
     */
    public function clear()
    {
        static::$services = array();

        return $this;
    }
} // END class ServiceMockeryLibrary
