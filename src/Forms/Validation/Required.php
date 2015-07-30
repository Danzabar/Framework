<?php namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\AbstractRule;

/**
 * Validation rule for a required field
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class Required extends AbstractRule
{

	/**
	 * Message
	 *
	 * @var String
	 */
	protected $message = 'This field is required';

	/**
	 * Validates the value
	 *
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function validate()
	{
		if (!empty($this->value) && !is_null($this->value) && $this->value != '')
		{
			return true;
		}

		return false;
	}

} // END class Required extends AbstractRule
