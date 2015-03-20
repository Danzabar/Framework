<?php

use Wasp\Test\TestCase;

/**
 * Test case for the Request Class
 *
 * @package Wasp
 * @subpackage Tests\Routing
 * @author Dan Cox
 */
class RequestTest extends TestCase
{
	/**
	 * Test Creating a request from the globals
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_createRequestFromGlobals()
	{
		$request = $this->DI->get('request');
		$request->fromGlobals();

		// Since theres no request the URI will be a blank string
		$this->assertEquals('', $request->getRequestUri());
	}

	/**
	 * Create a GET request
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_createSimulatedRequestGET()
	{
		$request = $this->DI->get('request');
		$request->make('/blog', 'GET', Array('page' => '3'));
		
		$this->assertEquals('/blog?page=3', $request->getRequestUri());
		$this->assertEquals(3, $request->query->get('page'));
	}

	/**
	 * Create a POST request
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_createSimulatedRequestPOST()
	{	
		$request = $this->DI->get('request');
		$request->make('/login', 'POST', Array('username' => 'bob'));

		$this->assertEquals('/login', $request->getRequestUri());
		$this->assertEquals('bob', $request->request->get('username'));
	}

} // END class RequestTest extends TestCase

