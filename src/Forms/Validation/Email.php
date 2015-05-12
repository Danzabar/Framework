<?php namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\Rule;

/**
 * Email validation rule
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class Email extends Rule
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
	}

} // END class Email extends Rule
