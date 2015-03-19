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
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($DI)
	{
		$this->DI = $DI;
	}

	/**
	 * Magic getter for accessing services from the DI
	 *
	 * @return Object
	 * @author Dan Cox
	 */
	public function __get($key)
	{
		return $this->DI->get($key);
	}


} // END class BaseController
