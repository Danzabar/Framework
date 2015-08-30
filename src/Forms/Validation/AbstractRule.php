<?php namespace Wasp\Forms\Validation;

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
     * @author Dan Cox
     */
    public function __construct($message = null)
    {
        if (!is_null($message))
        {
            $this->message = $message;
        }
    }

    /**
     * Sets the value
     *
     * @return Rule
     * @author Dan Cox
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
     * @author Dan Cox
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Validates the given value against the current rule
     *
     * @return Boolean
     * @author Dan Cox
     */
    abstract public function validate();


} // END class AbstractRule
