<?php namespace Wasp\Environment;

use Wasp\Environment\Environment,
	Wasp\Environment\EnvironmentInterface;

/**
 * Environment class for the console application
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
class Console extends Environment Implements EnvironmentInterface
{

	/**
	 * Setup environment properties
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->createDI();
	}

} // END class Console extends Environment Implements EnvironmentInterface
