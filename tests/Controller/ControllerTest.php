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
		$apiMock = new ServiceMockery('response.api');
		$apiMock->add();

		$filterMock = new ServiceMockery('filter');
		$filterMock->add();

		parent::setUp();
	}

	/**
	 * Test the json response
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_jsonResponse()
	{
		$action = 'Wasp\Test\Controller\Controller::jsonResponse';

		$dispatch = $this->DI->get('dispatcher');
		$response = $dispatch->dispatch($action);

		$this->assertTrue($response->isOk());
		$this->assertEquals('["1","2","3","4","5"]', $response->getContent());

		$content_type = $response->headers->get('content_type');

		$this->assertEquals('application/json', $content_type);
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
		$api = $this->DI->get('response.api');

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
		$api = $this->DI->get('response.api');
		
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

} // END class ControllerTest extends TestCase
