<?php

use Wasp\Application\DI;

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


} // END class DITest extends \PHPUnit_Framework_TestCase
