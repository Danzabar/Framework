<?php namespace Wasp\Environment;

use Wasp\Environment\Environment,
	Wasp\Environment\EnvironmentInterface;

/**
 * The development environment class
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
class Develop extends Environment implements EnvironmentInterface
{
	
	/**
	 * Setup the environment properties
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->createDIFromCache();
	}

} // END class Develop extends Environment implements EnvironmentInterface
