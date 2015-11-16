<?php

namespace Wasp\DI;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Registers DI extensions
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class ExtensionRegister
{
    /**
     * An Array of Qualified class names that act as DI extensions
     *
     * @var Array
     */
    protected static $extensions = array();

    /**
     * Registers extensions on the container
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function register($container)
    {
        foreach (static::$extensions as $extension) {
            $instance = new $extension;
            $container->registerExtension($instance);
            $container->loadFromExtension($instance->getAlias());
        }
    }

    /**
     * Loads an array of extensions into the class var
     *
     * @return ExtensionRegister
     */
    public function loadFromArray(Array $extensions)
    {
        static::$extensions = array_merge(static::$extensions, $extensions);

        return $this;
    }

    /**
     * Loads an array of extensions from a file
     *
     * @return ExtensionRegister
     */
    public function loadFromFile($file)
    {
        $fs = new Filesystem;

        if ($fs->exists($file)) {
            $extensions = require $file;

            $this->loadFromArray($extensions);
        }
    }

    /**
     * Returns registered extensions
     *
     * @return Array
     */
    public static function getExtensions()
    {
        return static::$extensions;
    }

    /**
     * Clears the extension list
     *
     * @return void
     */
    public static function clearExtensions()
    {
        static::$extensions = array();
    }
} // END class ExtensionRegister
