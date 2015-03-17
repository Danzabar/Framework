<?php namespace Wasp\Test;

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
		$this->application = new \Wasp\Application\Application;
		$this->application->loadEnv('test');
		$this->DI = $this->application->env->getDI();
	}

	/**
	 * Tear down test class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function tearDown()
	{
		\Mockery::close();

		// Clear the mocks
		$library = new \Wasp\DI\ServiceMockeryLibrary;
		$library->clear();
	}
	
} // END class TestCase extends \PHPUnit_Framework_TestCase
