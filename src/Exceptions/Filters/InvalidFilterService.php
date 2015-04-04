<?php namespace Wasp\Exceptions\Filters;

/**
 * Exception class for invalid filter services
 *
 * @package Wasp
 * @subpackage Exceptions\Filters
 * @author Dan Cox
 */
class InvalidFilterService extends \Exception
{

	/**
	 * The attempted service
	 *
	 * @var string
	 */
	protected $service;

	/**
	 * Fire exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($service, $code = 0, \Exception $previous = NULL)
	{
		$this->service = $service;

		parent::__construct("Attempting to use an unknown or invalid service $service for filter.", $code, $previous);
	}

	/**
	 * Returns the Attempted service
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getService()
	{
		return $this->service;
	}

} // END class InvalidFilterService extends \Exception
