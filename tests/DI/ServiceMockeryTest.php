<?php

use Wasp\DI\ServiceMockery,
	Wasp\DI\ServiceMockeryLibrary;

/**
 * Test case for the Service Mockery class and library
 *
 * @package Wasp
 * @subpackage Tests\DI
 * @author Dan Cox
 */
class ServiceMockeryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Instance of the library
	 *
	 * @var Object
	 */
	protected $library;

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$this->library = new ServiceMockeryLibrary;
	}

	/**
	 * Tear down test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function tearDown()
	{
		$this->library->clear();
	}

	/**
	 * Test that you can add and remove service mocks.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_basicAddingRemoving()
	{
		$mock = new ServiceMockery('database');
		$mock->add();

		$library = new ServiceMockeryLibrary;

		$this->assertEquals('database', $library->find('database'));
		$this->assertEquals('database', $mock->getLibrary()->find('database'));

		$mock->remove();

		$this->assertEquals(NULL, $library->find('database'));
		$this->assertEquals(NULL, $mock->getLibrary()->find('database'));
	}



} // END class ServiceMockeryTest extends \PHPUnit_Framework_TestCase
