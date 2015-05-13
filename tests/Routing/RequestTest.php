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

	/**
	 * Test getting the input from a request of different types
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getInput()
	{
		$request = $this->DI->get('request');

		// GET
		$request->make('/test', 'GET', Array('test' => 'foo'));

		$this->assertTrue($request->getInput()->has('test'));
		$this->assertEquals('foo', $request->getInput()->get('test'));

		// POST
		$request->make('/test', 'POST', Array('foo' => 'bar'));

		$this->assertTrue($request->getInput()->has('foo'));
		$this->assertEquals('bar', $request->getInput()->get('foo'));

		// PATCH
		$request->make('/test', 'PATCH', Array('bar' => 'foo'));

		$this->assertTrue($request->getInput()->has('bar'));
		$this->assertEquals('foo', $request->getInput()->get('bar'));

		// PUT
		$request->make('/test', 'PUT', Array('zim' => 'zam'));

		$this->assertTrue($request->getInput()->has('zim'));
		$this->assertEquals('zam', $request->getInput()->get('zim'));

		// DELETE
		$request->make('/test', 'DELETE', Array('zam' => 'zim'));

		$this->assertTrue($request->getInput()->has('zam'));
		$this->assertEquals('zim', $request->getInput()->get('zam'));
	}

} // END class RequestTest extends TestCase

