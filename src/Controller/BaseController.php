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
	 * Entiyu
	 *
	 * @var String
	 */
	protected $entity;

	/**
	 * Load the DI
	 * 
	 * @param Wasp\DI\DI $DI
	 * @param String $entity
	 *
	 * @author Dan Cox
	 */
	public function __construct($DI, $entity = NULL)
	{
		$this->DI = $DI;
		$this->entity = $entity;
	}

	/**
	 * Forwards the request to a different controller
	 *
	 * @param String $action
	 * @param Array $params
	 * @return void
	 * @author Dan Cox
	 */
	public function forward($action, Array $params = Array())
	{
		return $this->get('dispatcher')->dispatch($action, $params);
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
