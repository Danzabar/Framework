<?php namespace Wasp\Environment;

use Wasp\Environment\Environment,
	Wasp\Environment\EnvironmentInterface,
	Wasp\DI\Pass\ContainerInjectionPass;

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
		// Create the DI
		$this->DI->addCompilerPass(new ContainerInjectionPass);

		$this->createDIFromCache();
	}

} // END class Develop extends Environment implements EnvironmentInterface
