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
	 * Test the basic getters and setters of the application class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_init()
	{
		$app = new Application;

		// By Default our DI should point to src/config/core.yml
		$this->assertEquals('core', $app->getCoreServiceFile());
		$this->assertEquals(CONFIG, $app->getDIDirectory());

		// Set new values
		$app->setCoreServiceFile('services');
		$app->setDIDirectory(APPLICATION);

		$this->assertEquals('services', $app->getCoreServiceFile());
		$this->assertEquals(APPLICATION, $app->getDIDirectory());
	}

	/**
	 * Test building a DI instance from the App;
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildDI()
	{
		$app = new Application;
		
		// Keep the default settings
		$app->build();

		$this->assertInstanceOf('Wasp\Application\DI', $app->getDI());
	}
	
} // END class ApplicationTest extends \PHPUnit_Framework_TestCase
