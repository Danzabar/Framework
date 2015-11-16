<?php

namespace Wasp\Forms\Validation;

/**
 * The Base Rule class
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
abstract class AbstractRule
{

    /**
     * The field value
     *
     * @var Mixed
     */
    protected $value;

    /**
     * The error message associated with this rule
     *
     * @var String
     */
    protected $message;

    /**
     * Set up the rule
     *
     */
    public function __construct($message = null)
    {
        if (!is_null($message)) {
            $this->message = $message;
        }
    }

    /**
     * Sets the value
     *
     * @return Rule
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Returns the set message
     *
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Validates the given value against the current rule
     *
     * @return Boolean
     */
    abstract public function validate();
} // END class AbstractRule
