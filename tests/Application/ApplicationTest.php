<?php 

use Wasp\Application\Application,
	Wasp\DI\ServiceMockery;

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
	 * Tear down test env
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
	 * Test registering and deregistering environments
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_deregisterEnvironment()
	{
		$this->setExpectedException('Wasp\Exceptions\Application\UnknownEnvironment');

		$app = new Application;
		$app->registerEnvironment('TestEnv', 'TestEnvClass');

		$this->assertEquals('TestEnvClass', $app->getEnvironment('TestEnv'));

		$app->deregisterEnvironment('TestEnv');

		$app->getEnvironment('TestEnv');
	}

	/**
	 * Test deregistering an unknown environment
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_deregisterFail()
	{
		$this->setExpectedException('Wasp\Exceptions\Application\UnknownEnvironment');

		$app = new Application;
		$app->deregisterEnvironment('TestEnv');
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
		$app->loadEnv('test');

		$this->assertInstanceOf('Wasp\DI\DI', $app->env->getDI());
		$this->assertInstanceOf('Symfony\Component\Filesystem\Filesystem', $app->env->getDI()->get('fs'));
	}

	/**
	 * Test the respond function
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_mockRespond()
	{
		$requestMock = new ServiceMockery('request');
		$requestMock->add();
		$routerMock = new ServiceMockery('router');
		$routerMock->add();

		$app = new Application;
		$app->loadEnv('test');

		$request = $app->getDI()->get('request');
		$router = $app->getDI()->get('router');

		$request->shouldReceive('fromGlobals')->once()->andReturn($request);
		$request->shouldReceive('getURI')->once()->andReturn('/test');
		$router->shouldReceive('resolve')->once()->with('/test')->andReturn($router);
		$router->shouldReceive('send')->once();

		$app->respond();
	}

	
} // END class ApplicationTest extends \PHPUnit_Framework_TestCase
