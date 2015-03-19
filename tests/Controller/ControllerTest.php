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
		$api->shouldReceive('setCode')->with(200);
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
		$api->shouldReceive('setCode')->with(200);
		$api->shouldReceive('send');

		$dispatch = $this->DI->get('dispatcher');
		$dispatch->dispatch($action);	

	}

} // END class ControllerTest extends TestCase
