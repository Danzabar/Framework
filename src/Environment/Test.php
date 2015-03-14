<?php namespace Wasp\Environment;

use Wasp\Environment\Environment,
	Wasp\Environment\EnvironmentInterface,
	Wasp\DI\Pass\MockeryPass,
	Wasp\DI\Pass\ContainerInjectionPass;

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
		$this->DI->addCompilerPass(new MockeryPass);
		$this->DI->addCompilerPass(new ContainerInjectionPass);
		
		// No need to cache here. 
		$this->createDI();
	}


} // END class Test extends Environment Implements EnvironmentInterface
