<?php namespace Wasp\Controller;

/**
 * The Base Controller
 *
 * @package Wasp
 * @subpackage Controller
 * @author Dan Cox
 */
class BaseController
{

	/**
	 * Instance of the DI class
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Load the DI
	 *
	 * @author Dan Cox
	 */
	public function __construct($DI)
	{
		$this->DI = $DI;
	}

	/**
	 * Magic getter for accessing services from the DI
	 *
	 * @param String $key
	 * @return Object
	 * @author Dan Cox
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * Gets a service from the DependencyInjector
	 * This function exists for those services which have special characters and
	 * cant be fetched via the magic method
	 *
	 * @param String $service
	 * @return Object
	 * @author Dan Cox
	 */
	public function get($service)
	{
		return $this->DI->get($service);
	}


} // END class BaseController
