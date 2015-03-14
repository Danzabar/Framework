<?php

use Wasp\Environment\Environment,
	Wasp\Application\Application;

/**
 * Environment class test
 *
 * @package Wasp
 * @subpackage Tests\Environment
 * @author Dan Cox
 */
class EnvironmentTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * App instance
	 *
	 * @var Object
	 */
	protected $app;

	/**
	 * Setup Class env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{	
		$this->app = new Application;
	}

	/**
	 * Test building the DI
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildDI()
	{
		$env = new Environment();
		$env->load($this->app);

		$env->createDI('core');

		$this->assertInstanceOf('Wasp\DI\DI', $env->getDI());
		$this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $env->getDI()->getContainer());
	}

	/**
	 * Build the DI from the cache
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildDIFromCache()
	{
		$env = new Environment;
		$env->load($this->app);
		$env->createDIFromCache('core');

		$this->assertInstanceOf('Wasp\DI\DI', $env->getDI());
		$this->assertInstanceOf('Wasp\Application\Cache\AppCache', $env->getDI()->getContainer());
	}

	
} // END class EnvironmentTest extends \PHPUnit_Framework_TestCase

