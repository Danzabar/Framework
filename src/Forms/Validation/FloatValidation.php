<?php

namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\AbstractRule;

/**
 * Float type validation rule
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class FloatValidation extends AbstractRule
{

    /**
     * Message
     *
     * @var string
     */
    protected $message = 'A float was expected';

    /**
     * Validate value
     *
     * @return Boolean
     * @author Dan Cox
     */
    public function validate()
    {
        if (!empty($this->value)) {
            return filter_var($this->value, FILTER_VALIDATE_FLOAT);
        }

        return true;
    }
} // END class FloatValidation extends AbstractRule
