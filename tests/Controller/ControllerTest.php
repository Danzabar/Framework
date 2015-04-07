<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery;

/**
 * Test case for the controller class
 *
 * @package Wasp
 * @subpackage Tests\Controller
 * @author Dan Cox
 */
class ControllerTest extends TestCase
{
	
	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$apiMock = new ServiceMockery('response_api');
		$apiMock->add();

		$filterMock = new ServiceMockery('filter');
		$filterMock->add();

		parent::setUp();
	}


	/**
	 * Check dispatching a controller that returns a string
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dispatchStringResponse()
	{
		$action = 'Wasp\Test\Controller\Controller::returnString';
		$api = $this->DI->get('response_api');

		$api->shouldReceive('setContent')->with("Test");
		$api->shouldReceive('setStatusCode')->with(200);
		$api->shouldReceive('send');

		$dispatch = $this->DI->get('dispatcher');
		$dispatch->dispatch($action);	
	}

	/**
	 * Dispatch a response object
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dispatchResponse()
	{
		$action = 'Wasp\Test\Controller\Controller::returnObject';
		$api = $this->DI->get('response_api');
		
		$api->shouldReceive('setContent')->with("Foo");
		$api->shouldReceive('setStatusCode')->with(200);
		$api->shouldReceive('send');

		$dispatch = $this->DI->get('dispatcher');
		$dispatch->dispatch($action);	
	}

	/**
	 * Test the redirect object
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_redirect()
	{
		$action = 'Wasp\Test\Controller\Controller::redirect';

		$dispatch = $this->DI->get('dispatcher');
		
		$response = $dispatch->dispatch($action);

		$this->assertContains('Redirecting', $response->getContent());
	}

	/**
	 * Test dispatching with filters attached
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dispatchingWithFilters()
	{
		$filter = $this->DI->get('filter');
		$filter->shouldReceive('prepare')->once()->andReturn($filter);
		$filter->shouldReceive('fire')->with('test', 'method')->once();

		$api = $this->DI->get('response_api');
		$api->shouldReceive('setContent')->once();
		$api->shouldReceive('setStatusCode')->once();


		$dispatch = $this->DI->get('dispatcher');
		$dispatch->dispatch('Wasp\Test\Controller\Controller::returnObject', Array(), Array('before' => ['filter' => 'test', 'method' => 'method']));	
	}

} // END class ControllerTest extends TestCase
