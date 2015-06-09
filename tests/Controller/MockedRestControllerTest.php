<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery;


/**
 * Rest controller mocked tests
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class MockedRestControllerTest extends TestCase
{
	
	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		// Mock for database
		$m = new ServiceMockery('database');
		$m->add();

		$mPaginator = new ServiceMockery('paginator');
		$mPaginator->add();

		parent::setUp();

		$this->DI->get('route')->resource('test', '/test', 'Wasp\Test\Entity\Entities\Contact');
	}

	/**
	 * Test that the delete route fails gracefully
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_DeleteRouteFail()
	{
		$db = $this->DI->get('database');

		$db->shouldReceive('setEntity')->once()->andReturn($db);
		$db->shouldReceive('find')->with(1)->andReturn($db);
		$db->shouldReceive('delete')->andThrow(new Exception('Test'));
		
		// Fabricate the request
		$this->DI->get('request')->make('/test/delete/1', 'DELETE', []);
		$response = $this->DI->get('router')->resolve('/test/delete/1');

		$obj = json_decode($response->getContent());

		$this->assertEquals(400, $response->getStatusCode());
		$this->assertEquals('Test', $obj->error);
		$this->assertEquals('error', $obj->status);
	}

	/**
	 * Test the ALL route with a database exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_allFailingRoute()
	{
		$paginator = $this->DI->get('paginator');

		$paginator->shouldReceive('setEntity')->andReturn($paginator);
		$paginator->shouldReceive('query')->andThrow(new Exception('Test'));

		$this->DI->get('request')->make('/test', 'GET', ['pageSize' => 15]);
		$response = $this->DI->get('router')->resolve('/test');

		$obj = json_decode($response->getContent());

		$this->assertEquals(400, $response->getStatusCode());
		$this->assertEquals('Test', $obj->error);
	}


} // END class MockedRestController extends TestCase
