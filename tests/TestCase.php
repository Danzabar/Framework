<?php

use Wasp\Application\Application
	\Mockery as m;

/**
 * Test case base that provides application access
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class TestCase extends \PHPUnit_Framework_TestCase
{

	/**
	 * Instance of the WASP Application
	 *
	 * @var Object
	 */
	protected $application;

	/**
	 * Instance of the WASP DI
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Set up test class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$this->application = new Application;
		$this->application->build();

		$this->DI = $this->application->getDI();
	}

	/**
	 * Tear down test class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function tearDown()
	{
		m::close();

		$this->DI->clearMocks();
	}
	
} // END class TestCase extends \PHPUnit_Framework_TestCase
