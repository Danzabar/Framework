<?php namespace Wasp\Test;

use Wasp\DI\DICompilerPassRegister,
	Symfony\Component\DomCrawler\Crawler;


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
	 * The response
	 *
	 * @var Symfony\Component\HttpFoundation\Response
	 */
	protected $response;

	/**
	 * Instance of the Dom Crawler
	 *
	 * @var Symfony\Component\DomCrawler\Crawler
	 */
	protected $crawler;

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
			$this->DI->get('command.loader')->fromArray($this->commands);	
		}
	}

	/**
	 * Uses the application to build a response object based on the current state of the request class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function respond()
	{
		$this->response = $this->application->react();

		// Create the crawler for any html assertions
		$this->crawler = new Crawler($this->response->getContent());
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

		DICompilerPassRegister::clear();
	}
	
} // END class TestCase extends \PHPUnit_Framework_TestCase
