<?php

use Wasp\DI\DI,
	Wasp\DI\ServiceMockery,
	Wasp\DI\Pass\MockeryPass;


/**
 * Testing the Mockery pass for the DI unit.
 *
 * @package Wasp
 * @subpackage Tests\DI
 * @author Dan Cox
 */
class MockeryPassTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * The DI Instance
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		// Set up a new DI;
		$this->DI = new DI(dirname(__DIR__));
		$this->DI->build()->load('service');
	}

	/**
	 * Register the compiler pass and compile the DI
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_compileAndRegister()
	{
		$mock = new ServiceMockery('excep');	
		$mock->add();

		$this->DI->addCompilerPass(new MockeryPass);
		$this->DI->compile();

		$this->DI->get('excep')->shouldReceive('test')->andReturn(true);

		$this->assertTrue($this->DI->get('excep')->test());
	}
	
} // END class MockeryPassTest extends \PHPUnit_Framework_TestCase
