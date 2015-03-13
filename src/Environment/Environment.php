<?php namespace Wasp\Environment;

use Wasp\Application\Application,
	Wasp\DI\DI;


/**
 * Base environment class, Determines actions taken on Application Launch.
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
class Environment
{
	/**
	 * The App instance
	 *
	 * @var Object
	 */
	protected $App;

	/**
	 * The DI instance
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Sets the App and DI instances and Calls the Child Class function if exists
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function load(Application $App)
	{
		$this->App = $App;

		// If the Child Environment Class has a Setup function, call it.
		if(method_exists($this, 'setup'))
		{
			$this->setup();
		}
	}


} // END class Environment
