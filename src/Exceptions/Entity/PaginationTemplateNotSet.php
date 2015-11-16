<?php

namespace Wasp\Exceptions\Entity;

/**
 * Exception class for generating pagination templates
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class PaginationTemplateNotSet extends \Exception
{
    /**
     * Fire exception
     *
     * @param Integer $code
     * @param Exception $previous
     */
    public function __construct($code = 0, \Exception $previous = null)
    {
        parent::__construct('The pagination template has not been set.', $code, $previous);
    }
} // END class PaginationTemplateNotSet extends \Exception
