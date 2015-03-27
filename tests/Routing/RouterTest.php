<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery;

/**
 * Test case for the router class
 *
 * @package Wasp
 * @subpackage Tests\Routing
 * @author Dan Cox
 */
class RouterTest extends TestCase
{

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$mock = new ServiceMockery('dispatcher');
		$mock->add();

		parent::setUp();
	}

	/**
	 * Test resolving a defined route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_resolveRoute()
	{
		$dispatcher = $this->DI->get('dispatcher');
		$dispatcher->shouldReceive('dispatch')->with('TestController::Action', Array())->once();

		// Fabricate a request
		$this->DI->get('request')->make('/test', 'GET');

		$this->DI->get('route')
				 ->add('test.router', '/test', ['GET'], ['controller' => 'TestController::Action']);

		$this->DI->get('router')->resolve('/test');
	}

} // END class RouterTest extends TestCase
