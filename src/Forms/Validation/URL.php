<?php

namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\AbstractRule;

/**
 * URL Validation rule
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class URL extends AbstractRule
{

    /**
     * Message
     *
     * @var String
     */
    protected $message = 'A valid URL was expected';

    /**
     * Validate
     *
     * @return Boolean
     */
    public function validate()
    {
        if (!empty($this->value)) {
            return filter_var($this->value, FILTER_VALIDATE_URL);
        }

        return true;
    }
} // END class URL extends AbstractRule
