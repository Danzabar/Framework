<?php namespace Wasp\DI;

/**
 * Dependency injection Awareness
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
Trait DependencyInjectionAwareTrait {

    /**
     * The DI instance
     *
     * @var Object
     */
    protected $DI;

    /**
     * Sets the DI Container
     *
     * @author Dan Cox
     */
    public function setDI($DI)
    {
        $this->DI = $DI;
    }

    /**
     * Returns the DI
     *
     * @return DI
     * @author Dan Cox
     */
    public function getDI()
    {
        return $this->DI;
    }
}
