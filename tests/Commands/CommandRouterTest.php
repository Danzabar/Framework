<?php

use Wasp\Test\TestCase;

/**
 * Test Case for the Command Router Class
 *
 * @package Wasp
 * @subpackage Test\Commands
 * @author Dan Cox
 */
class CommandRouterTest extends TestCase
{
	
	/**
	 * Test Adding routes
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addingRoutes()
	{
		$router = $this->DI->get('command.router');

		$router->addRoute('test', 'test');

		$routes = $router->getRoutes();
		$this->assertEquals(1, count($routes));
	}

} // END class CommandRouterTest extends TestCase
