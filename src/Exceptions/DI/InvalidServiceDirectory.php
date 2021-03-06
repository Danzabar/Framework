<?php

namespace Wasp\Exceptions\DI;

/**
 * Exception for when the service definition directory has not been set
 *
 * @package Wasp
 * @subpackage Exceptions\DI
 * @author Dan Cox
 */
class InvalidServiceDirectory extends \Exception
{

    /**
     * The DI Object
     *
     * @var Object
     */
    protected $DI;

    /**
     * Fire Exception
     *
     * @param \Wasp\DI\DI $DI
     * @return void
     */
    public function __construct($DI, $code = 0, \Exception $previous = null)
    {
        $this->DI = $DI;

        $msg = "The service directory must be set before calling build on the Dependency Injection class.";

        parent::__construct($msg, $code, $previous);
    }

    /**
     * Returns the DI Object
     *
     * @return DI
     */
    public function getDI()
    {
        return $this->DI;
    }
} // END class InvalidServiceDirectory extends \Exception
