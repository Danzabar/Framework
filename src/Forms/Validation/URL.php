<?php namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\Rule;

/**
 * URL Validation rule
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class URL extends Rule
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
	 * @author Dan Cox
	 */
	public function validate()
	{
		return filter_var($this->value, FILTER_VALIDATE_URL);
	}


} // END class URL extends Rule
