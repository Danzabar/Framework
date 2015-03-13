<?php 

use Wasp\Application\Application;

/**
 * Test Case for the Application Class
 *
 * @package Wasp
 * @subpackage Tests\Application
 * @author Dan Cox
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Test registering a test environment
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_registeringEnvironment()
	{
		$app = new Application;
		$app->registerEnvironment('TestEnv', 'TestEnvClass');

		$this->assertEquals('TestEnvClass', $app->getEnvironment('TestEnv'));
	}

	/**
	 * Test the failing of get environment
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getEnvironmentFail()
	{
		$this->setExpectedException("Wasp\Exceptions\Application\UnknownEnvironment");

		$app = new Application;
		$app->getEnvironment("None");
	}

	/**
	 * Test loading an environment
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_loadEnvironment()
	{
		$app = new Application;
		$app->loadEnv('develop');
	}

	
} // END class ApplicationTest extends \PHPUnit_Framework_TestCase
