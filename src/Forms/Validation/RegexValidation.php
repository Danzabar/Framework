<?php namespace Wasp\Forms\Validation;

use Wasp\Forms\Validation\AbstractRule;

/**
 * Regexp validation rule
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class RegexValidation extends AbstractRule
{
	/**
	 * Message
	 *
	 * @var String
	 */
	protected $message = 'This value fails filter criteria';

	/**
	 * Regular expression
	 *
	 * @var String
	 */
	protected $regexp;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($message = null, $regexp = null)
	{
		if (!is_null($message))
		{
			$this->message = $message;
		}

		$this->regexp = $regexp;
	}

	/**
	 * Validate against given regexp
	 *
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function validate()
	{
		if (!is_null($this->regexp))
		{
			return filter_var($this->value, FILTER_VALIDATE_REGEXP, Array('options' => Array('regexp' => $this->regexp)));
		}

		// No regexp was given.
		return true;
	}

} // END class RegexValidation extends AbstractRule
