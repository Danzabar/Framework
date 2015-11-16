<?php

namespace Wasp\DI;

/**
 * Dependency injection Awareness
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
trait DependencyInjectionAwareTrait
{

    /**
     * The DI instance
     *
     * @var Object
     */
    protected $DI;

    /**
     * Sets the DI Container
     *
     */
    public function setDI($DI)
    {
        $this->DI = $DI;
    }

    /**
     * Returns the DI
     *
     * @return DI
     */
    public function getDI()
    {
        return $this->DI;
    }
}
