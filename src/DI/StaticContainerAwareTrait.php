<?php

namespace Wasp\DI;

/**
 * Static container awareness trait
 *
 * @package Wasp
 * @subpackage Di
 * @author Dan Cox
 */
trait StaticContainerAwareTrait
{
    /**
     * Gets a service using the static container
     *
     * @return object
     */
    public static function get($service)
    {
        $container = DI::getContainer();

        return $container->get($service);
    }

    /**
     * Gets a parameter using the static container
     *
     * @return mixed
     */
    public static function param($key)
    {
        $container = DI::getContainer();

        return $container->getParameter($key);
    }
}
