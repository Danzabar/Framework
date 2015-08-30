<?php

namespace Wasp\Exceptions\Database;

/**
 * Exception class for invalid connections
 *
 * @package Wasp
 * @subpackage Exceptions\Database
 * @author Dan Cox
 */
class InvalidConnection extends \Exception
{

    /**
     * The name of the connection attempted
     *
     * @var string
     */
    protected $connection;

    /**
     * Fire Exception
     *
     * @author Dan Cox
     */
    public function __construct($connection, $code = 0, \Exception $previous = null)
    {
        $this->connection = $connection;

        parent::__construct("An attempt was made to use an invalid connection: $connection", $code, $previous);
    }

    /**
     * Returns the Connection Var
     *
     * @return String
     * @author Dan Cox
     */
    public function getConnection()
    {
        return $this->connection;
    }
} // END class InvalidConnection extends \Exception
