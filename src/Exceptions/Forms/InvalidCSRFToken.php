<?php

namespace Wasp\Exceptions\Forms;

/**
 * Exception class for an invalid csrf token
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class InvalidCSRFToken extends \Exception
{
    /**
     * Fire exception
     *
     * @param Integer $code
     * @param Exception $previous
     * @author Dan Cox
     */
    public function __construct($code = 0, \Exception $previous = NULL)
    {
        parent::__construct("The CSRF token provided is invalid.", $code, $previous);
    }

} // END class InvalidCSRFToken extends \Exception
