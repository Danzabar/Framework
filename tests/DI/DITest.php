<?php

use Wasp\DI\DI;

/**
 * Test case for the DI class
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class DITest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Basic test getting and setting directory
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_setGetDirectory()
	{
		$di = new DI('test');

		$this->assertEquals('test', $di->getDirectory());
		$di->setDirectory('foo');
		$this->assertEquals('foo', $di->getDirectory());
	}

	/**
	 * Test Building DI
	 *
	 * @return void
	 * @author Dan Cox
	 */		
	public function test_Build()
	{
		$di = new DI;
		$di->setDirectory(__DIR__);
		$di->build()->load('service');

		$this->assertInstanceOf('Service', $di->get('service'));
		$this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $di->get('service')->getDI());
		$this->assertEquals('value', $di->param('test'));
	}

	/**
	 * Test that an exception is thrown if trying to build without a directory set
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildWithoutDirectory()
	{
		$this->setExpectedException('Wasp\Exceptions\DI\InvalidServiceDirectory');

		$di = new DI;
		$di->build();
	}

	/**
	 * Test the caching functionality
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_cache()
	{
		$di = new DI(CONFIG);
		$di->buildContainerFromCache('core');

		$this->assertTrue(class_exists('Wasp\Application\Cache\AppCache'));
		$this->assertInstanceOf('Wasp\Application\Cache\AppCache', $di->get('connections')->getDI());
	}

	/**
	 * Test the extension register function from the DI class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function  test_extensionRegistrar()
	{
		$di = new DI(CONFIG);
		$di->build()->load('core');

		$this->assertInstanceOf('Wasp\DI\ExtensionRegister', $di->extensions());
	}


} // END class DITest extends \PHPUnit_Framework_TestCase
