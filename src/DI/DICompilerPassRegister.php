<?php

namespace Wasp\DI;

/**
 * Registers Compiler pass classes that are triggered in the environment
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class DICompilerPassRegister
{

    /**
     * An Array of passes
     *
     * @var Array
     */
    protected static $passes = array();

    /**
     * Returns the passes array
     *
     * @return Array
     */
    public static function getPasses()
    {
        return static::$passes;
    }

    /**
     * Adds a class to the array
     *
     * @param String|Array $class - Qualified class name for the pass
     * @return void
     */
    public static function add($class)
    {
        if (!is_array($class)) {
            $class = array($class);
        }

        static::$passes = array_merge(static::$passes, $class);
    }

    /**
     * Removes a single class from the register
     *
     * @param String $class
     * @return void
     */
    public static function remove($class)
    {
        if ($key = array_search($class, static::$passes) !== false) {
            unset(static::$passes[$key]);
        }

        static::$passes = array_values(static::$passes);
    }

    /**
     * Clears the list of registered classes
     *
     * @return void
     */
    public static function clear()
    {
        static::$passes = array();
    }
} // END class DICompilerPassRegister
