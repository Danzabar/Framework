<?php namespace Wasp\Exceptions\Templating;

/**
 * Exception class for directory not set
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class DirectoryNotSet extends \Exception
{

	/**
	 * Fire exception
	 *
	 * @author Dan Cox
	 */
	public function __construct($code = 0, \Exception $previous = NULL)
	{
		parent::__construct(
			"Attempted to create an instance of Delegation Engine without a directory set",
			$code,
			$previous
		);
	}

} // END class DirectoryNotSet extends \Exception
