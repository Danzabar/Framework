<?php namespace Wasp\Environment;

use Wasp\Environment\Environment,
	Wasp\Environment\EnvironmentInterface;

/**
 * Environment Class for the Test environment.
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
class Test extends Environment Implements EnvironmentInterface
{

	/**
	 * Setup the environment properties
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{

	}


} // END class Test extends Environment Implements EnvironmentInterface
