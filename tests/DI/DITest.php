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

	public function test_Build()
	{
		$di = new DI;
		$di->setDirectory(__DIR__);
		$di->build()->load('service');

		$this->assertInstanceOf('Wasp\Exceptions\DI\InvalidServiceDirectory', $di->get('excep'));
		$this->assertEquals('value', $di->param('test'));
	}


} // END class DITest extends \PHPUnit_Framework_TestCase
