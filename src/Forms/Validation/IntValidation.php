<?php

namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\AbstractRule;

/**
 * Validation rule for int's
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class IntValidation extends AbstractRule
{

    /**
     * Message
     *
     * @var String
     */
    protected $message = 'An integer was expected';

    /**
     * Validate value
     *
     * @return Boolean
     * @author Dan Cox
     */
    public function validate()
    {
        if (!empty($this->value))
        {
            return filter_var($this->value, FILTER_VALIDATE_INT);
        }

        return true;
    }

} // END class IntValidation extends AbstractRule
