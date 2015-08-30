<?php

use Wasp\DI\DependencyInjectionAwareTrait;


/**
 * Just a test service
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class Service
{
    use DependencyInjectionAwareTrait;
    
    /**
     * An Instance of the Test Library
     *
     * @var Object
     */
    protected $library;

    /**
     * Set up class dependencies
     *
     * @return void
     * @author Dan Cox
     */
    public function __construct($library = NULL)
    {
        $this->library = $library;
    }

    /**
     * Test func
     *
     * @return void
     * @author Dan Cox
     */
    public function fs()
    {
        return $this->DI->get('fs');
    }

    /**
     * Test fun
     *
     * @return void
     * @author Dan Cox
     */
    public function useLibrary()
    {
        return $this->library->foo();
    }
    
} // END class Service
