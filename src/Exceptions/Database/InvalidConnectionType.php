<?php namespace Wasp\Exceptions\Database;

/**
 * Exception class for an invalid connection type
 *
 * @package Wasp
 * @subpackage Exceptions\Database
 * @author Dan Cox
 */
class InvalidConnectionType extends \Exception
{

    /**
     * The Attempted type
     *
     * @var string
     */
    protected $type;

    /**
     * The Allowed types
     *
     * @var Array
     */
    protected $allowed;

    /**
     * Fire exception
     *
     * @author Dan Cox
     */
    public function __construct($type, Array $allowed, $code = 0, \Exception $previous = NULL)
    {
        $this->type = $type;
        $this->allowed = $allowed;

        parent::__construct("An invalid connection type was used $type, connections can be one of: " . join(',', array_keys($allowed)), $code, $previous);
    }

    /**
     * Returns the type
     *
     * @return string
     * @author Dan Cox
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the allowed type
     *
     * @return Array
     * @author Dan Cox
     */
    public function getAllowed()
    {
        return $this->allowed;
    }

} // END class InvalidConnectionType extends \Exception
