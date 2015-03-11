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
	 * Test adding a new mockery instance to the library
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addingToLibrary()
	{
		$mock = new ServiceMockery('database');
		$mock->add();

		$this->assertEquals($mock->getMock(), $this->library->find('database'));
	}

	/**
	 * A proper version of the above test
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addingWithMockeryMethods()
	{
		$mock = new ServiceMockery('database');
		$mock->add();

		// Add some mockery methods
		$mock->getMock()->shouldReceive('test')->andReturn(true);

		$m = $this->library->find('database');

		$this->assertTrue($m->test());
	}

	/**
	 * Test removing the mockery
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addRemove()
	{
		$mock = new ServiceMockery('database');
		$mock->add();

		$this->assertEquals($mock->getMock(), $this->library->find('database'));

		$mock->remove();

		$this->assertNull($this->library->find('database'));
	}

	/**
	 * Test getting all and clearing from the library
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_allandClear()
	{
		$mock1 = new ServiceMockery('db');
		$mock2 = new ServiceMockery('test');

		$mock1->add();
		$mock2->add();

		$this->assertTrue(count($this->library->all()) == 2);
		
		$this->library->clear();

		$this->assertEquals(0, count($this->library->all()));
	}


} // END class ServiceMockeryTest extends \PHPUnit_Framework_TestCase
