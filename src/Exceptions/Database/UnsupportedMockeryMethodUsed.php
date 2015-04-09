<?php namespace Wasp\Exceptions\Database;

/**
 * Exception class for an unsupported database mockery method
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class UnsupportedMockeryMethodUsed extends \Exception
{
	/**
	 * The Attempted method
	 *
	 * @var string
	 */
	protected $method;	

	/**
	 * Fire exception
	 *
	 * @author Dan Cox
	 */
	public function __construct($method, $code = 0, \Exception $previous = NULL)
	{
		$this->method = $method;

		parent::__construct("An unsupported database mockery method was used: $method", $code, $previous);
	}

	/**
	 * Returns the attempted method
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getMethod()
	{
		return $this->method;
	}

} // END class UnsupportedMockeryMethodUsed extends \Exception
