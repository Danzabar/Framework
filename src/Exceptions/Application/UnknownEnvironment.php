<?php

namespace Wasp\Exceptions\Application;

/**
 * Exception class for unknown environments
 *
 * @package Wasp
 * @subpackage Exceptions\Application
 * @author Dan Cox
 */
class UnknownEnvironment extends \Exception
{
    /**
     * The Name of the Attempted Environment
     *
     * @var String
     */
    protected $env;

    /**
     * Fire exception
     *
     * @param string $env
     * @return void
     * @author Dan Cox
     */
    public function __construct($env, $code = 0, \Exception $previous = null)
    {
        $this->env = $env;

        parent::__construct("Attempted action against an unknown or un-registered environment: $env", $code, $previous);
    }

    /**
     * Returns the environment
     *
     * @return String
     * @author Dan Cox
     */
    public function getEnvironment()
    {
        return $this->env;
    }
} // END class UnknownEnvironment extends \Exception
