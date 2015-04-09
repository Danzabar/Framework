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
	 * An array of compiler passes to register once the DI is created
	 *
	 * @var Array
	 */
	protected $passes;

	/**
	 * Instance of the WASP DI
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * The environment on which to build
	 *
	 * @var String
	 */
	protected $env;	

	/**
	 * Set up test class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		if(is_null($this->env))
		{
			$this->env = 'test';
		}

		$this->registerPasses();

		$this->application = new \Wasp\Application\Application;
		$this->application->loadEnv($this->env);
		$this->DI = $this->application->env->getDI();

		if(property_exists($this, 'commands') && is_array($this->commands))
		{
			$this->DI->get('commandloader')->fromArray($this->commands);	
		}
	}

	/**
	 * Registers passes specified in the test
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function registerPasses()
	{
		if (!is_null($this->passes))
		{
			DICompilerPassRegister::add($this->passes);
		}
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

		$extensions = new \Wasp\DI\ExtensionRegister;
		$extensions->clearExtensions();
	}
	
} // END class TestCase extends \PHPUnit_Framework_TestCase
