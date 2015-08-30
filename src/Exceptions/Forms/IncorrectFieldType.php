<?php

namespace Wasp\Exceptions\Forms;

/**
 * Exception class for invalid field types on forms
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class IncorrectFieldType extends \Exception
{
    /**
     * The Attempted Type
     *
     * @var String
     */
    protected $type;

    /**
     * Fire exception
     *
     * @author Dan Cox
     */
    public function __construct($type, $code = 0, \Exception $previous = null)
    {
        $this->type = $type;
        parent::__construct("Incorrect field type used: $type", $code, $previous);
    }

    /**
     * Returns the type
     *
     * @return String
     * @author Dan Cox
     */
    public function getType()
    {
        return $this->type;
    }

} // END class IncorrectFieldType extends \Exception
