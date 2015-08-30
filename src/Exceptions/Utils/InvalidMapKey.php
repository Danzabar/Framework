<?php

namespace Wasp\Exceptions\Utils;

/**
 * Exception class for using the argument map trait with an invalid key
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class InvalidMapKey extends \Exception
{
    /**
     * The attempted key
     *
     * @var string
     */
    protected $key;

    /**
     * Fire exception
     *
     * @param String $key
     * @param Integer $code
     * @param Exception $previous
     * @return void
     * @author Dan Cox
     */
    public function __construct($key, $code = 0, \Exception $previous = null)
    {
        $this->key = $key;

        parent::__construct("Attempt to use argument map values with an invalid key: $key", $code, $previous);
    }

    /**
     * Returns the key
     *
     * @return String
     * @author Dan Cox
     */
    public function getKey()
    {
        return $this->key;
    }
} // END class InvalidMapKey extends \Exception
