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
	 * Test adding mocks
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_mockObjects()
	{
		$di = new DI;
		$di->addMock('foo', new StdClass);
		$di->addMock('bar', new StdClass);

		$this->assertTrue(array_key_exists('foo', $di->getMocks()));

		$di->removeMock('foo');

		$this->assertFalse(array_key_exists('foo', $di->getMocks()));

		$di->clearMocks();

		$this->assertEquals(Array(), $di->getMocks());
	}

	/**
	 * Testing the params 
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_mockParams()
	{
		$di = new DI;
		$di->addParam('test', 'foo');
		$di->addParam('fake', 'bar');

		$this->assertContains('foo', $di->getParams());

		$di->removeParam('test');

		$this->assertFalse(in_array('foo', $di->getParams()));

		$di->clearMocks();

		$this->assertEquals(Array(), $di->getParams());
	}

	/**
	 * Build the DI and add mocks/params
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_Build()
	{
		$di = new DI;
		$di->setDirectory(__DIR__);
		$di->build()->load('service');

		$this->assertInstanceOf('Wasp\Exceptions\DI\InvalidServiceDirectory', $di->get('excep'));
		$this->assertEquals('value', $di->param('test'));

		// Mock the values
		$mockClass = new StdClass;
		$mockClass->test = 'foo';

		$di->addMock('excep', $mockClass);

		$this->assertEquals('foo', $di->get('excep')->test);

		$di->addParam('test', 'bar');

		$this->assertEquals('bar', $di->param('test'));

		$di->clearMocks();

		$this->assertInstanceOf('Wasp\Exceptions\DI\InvalidServiceDirectory', $di->get('excep'));
		$this->assertEquals('value', $di->param('test'));
	}


} // END class DITest extends \PHPUnit_Framework_TestCase
