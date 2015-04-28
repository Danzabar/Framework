<?php

use Wasp\Test\TestCase;

/**
 * Test Class for the Route Class
 *
 * @package Wasp
 * @subpackage Tests\Routing
 * @author Dan Cox
 **/
class RouteTest extends TestCase
{

	/**
	 * Test Adding a route that can be found in the Collection
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function test_addRoute()
	{
		$route = $this->DI->get('route');
		$collection = $this->DI->get('route_collection');

		$route->add(
			'test.route', 
			'/',
			Array('GET'),
			Array('controller' => 'Test')
		);

		$match = $collection->get('test.route');

		$this->assertEquals(Array('controller' => 'Test'), $match->getDefaults());
	}

	/**
	 * Test adding a group of routes
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function test_addGroup()
	{
		$route = $this->DI->get('route');
		$collection = $this->DI->get('route_collection');

		$route->group(Array(), function($route) {
			$route->addPrefix('/group');
			$route->add('test.group', '', Array('GET'), Array('controller' => 'test'));
		});

		$match = $collection->get('test.group');
		$this->assertEquals('/group', $match->getPath());
	}

	/**
	 * Test setting up and finding restful routes
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_restRoutes()
	{
		$route = $this->DI->get('route');
		$collection = $this->DI->get('route_collection');

		$route->rest('blogs', '/blog', 'BlogController');

		$match = $collection->get('blogs.new');

		$this->assertEquals('/blog/new', $match->getPath());
	}

	/**
	 * Test the route/router journey fully
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_runningRouteWithRealResponse()
	{
		// Add a route
		$this->DI->get('route')->add('test.json', '/json', Array("GET"), Array('controller' => 'Wasp\Test\Controller\Controller::jsonResponse'));

		// Create a request
		$request = $this->DI->get('request');
		$request->make('/json', 'GET');

		// Response to this
		$response = $this->DI->get('router')->resolve($request->getRequestUri());

		// Check the response
		$this->assertTrue($response->isOk());
		$this->assertEquals('["1","2","3","4","5"]', $response->getContent());

		$this->assertEquals('application/json', $response->headers->get('content_type'));
	}

} // END class RouteTest extends TestCase
