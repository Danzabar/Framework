<?php namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\AbstractRule;

/**
 * Email validation rule
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class Email extends AbstractRule
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'A valid email was expected';

    /**
     * Validate the value
     *
     * @return Boolean
     * @author Dan Cox
     */
    public function validate()
    {
        if (!empty($this->value))
        {
            return filter_var($this->value, FILTER_VALIDATE_EMAIL);
        }

        return true;
    }

} // END class Email extends AbstractRule
