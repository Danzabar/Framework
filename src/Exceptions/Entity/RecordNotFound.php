<?php

namespace Wasp\Exceptions\Entity;

/**
 * Exception class for records not found
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class RecordNotFound extends \Exception
{
    /**
     * Fire exception
     *
     * @param Integer $code
     * @param \Exception $previous
     */
    public function __construct($code = 0, \Exception $previous = null)
    {
        parent::__construct("Query found no results.", $code, $previous);
    }
} // END class RecordNotFound extends \Exception
