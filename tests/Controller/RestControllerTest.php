<?php

use Wasp\Test\TestCase,
	Wasp\Test\Entity\Entities\Contact;

/**
 * Test case for the rest controller
 *
 * @package Wasp
 * @subpackage Tests\Controler
 * @author Dan Cox
 */
class RestControllerTest extends TestCase
{
	
	/**
	 * An Array of Passes this test uses
	 *
	 * @var Array
	 */
	protected $passes = [
		'Wasp\DI\Pass\DatabaseMockeryPass'
	];

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		// Set up the test DB
		$this->DI->get('database')->create(ENTITIES);

		// Add a resource route
		$this->DI->get('route')->resource('test', '/test', 'Wasp\Test\Entity\Entities\Contact');
	}

	/**
	 * Test the show route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_showRoute()
	{
		// Create the entry first
		$ent = new Contact;
		$ent->name = 'Test';
		$ent->message = 'Test message';
		$ent->save();

		$response = $this->fakeRequest('/test/1', 'GET');

		$obj = json_decode($response->getContent());

		$this->assertEquals('Test', $obj->name);
	}

	/**
	 * Test the show route with an invalid identifier
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_showRouteInvalidIdentifier()
	{
		$response = $this->fakeRequest('/test/1', 'GET');

		$obj = json_decode($response->getContent());
		$this->assertEquals('Invalid identifier', $obj->status);
		$this->assertEquals(404, $response->getStatusCode());
	}

	/**
	 * Test a successful create route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_createRoute()
	{
		$data = ['name' => 'CreateTest1', 'message' => 'This was created in test_createRoute'];

		$response = $this->fakeRequest('/test/new', 'POST', $data);

		// Check the response
		$obj = json_decode($response->getContent());

		$ent = Contact::db()->find($obj->data->id);

		$this->assertEquals('success', $obj->status);
		$this->assertEquals('CreateTest1', $obj->data->name);

		$this->assertEquals($ent->name, $obj->data->name);
	}

	/**
	 * Test create route returning a validation error
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_createRouteValidationError()
	{
		$data = ['name' => 'CreateTest2', 'message' => ''];

		$response = $this->fakeRequest('/test/new', 'POST', $data);
		
		$obj = json_decode($response->getContent());

		$this->assertEquals('message', $obj->errors[0]->property);
		$this->assertEquals('', $obj->errors[0]->value);
	}

	/**
	 * Test a successful update route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_updateRoute()
	{
		$ent = new Contact();
		$ent->name = 'UpdateTest1';
		$ent->message = 'MyMessage';
		$ent->save();

		$data = ['message' => 'The New message'];
		$response = $this->fakeRequest('/test/update/1', 'PATCH', $data);

		$obj = json_decode($response->getContent());

		$record = Contact::db()->find($obj->data->id);

		$this->assertEquals('success', $obj->status);
		$this->assertEquals('UpdateTest1', $obj->data->name);
		$this->assertEquals('The New message', $obj->data->message);
		$this->assertEquals($record->message, $obj->data->message);
	}

	/**
	 * Test update route with an invalid identifier
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_updateRouteInvalid()
	{
		$response = $this->fakeRequest('/test/update/1', 'PATCH');

		$obj = json_decode($response->getContent());

		$this->assertEquals(404, $response->getStatusCode());
		$this->assertEquals('Invalid identifier', $obj->status);
	}

	/**
	 * Test validation error on update route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_updateRouteValidation()
	{
		$ent = new Contact();
		$ent->name = 'UpdateTest2';
		$ent->message = 'Validation error';
		$ent->save();

		$response = $this->fakeRequest('/test/update/1', 'PATCH', ['message' => '']);
		
		$obj = json_decode($response->getContent());

		$this->assertEquals('message', $obj->errors[0]->property);	
	}

	/**
	 * Test a successful delete route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_deleteRoute()
	{
		$ent = new Contact();
		$ent->name = 'Delete1Test';
		$ent->message = 'Test';
		$ent->save();

		$response = $this->fakeRequest('/test/delete/1', 'DELETE');

		$obj = json_decode($response->getContent());

		$records = Contact::db()->get();

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals('success', $obj->status);
		$this->assertEquals(0, count($records));
	}

	/**
	 * Test a delete route where the identifier is invalid
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_deleteRouteInvalid()
	{
		$response = $this->fakeRequest('/test/delete/1', 'DELETE');

		$obj = json_decode($response->getContent());

		$this->assertEquals(404, $response->getStatusCode());
		$this->assertEquals('Invalid identifier', $obj->status);
	}

	/**
	 * Test the All route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_allRoute()
	{
		for ($i = 0; $i < 10; $i++)
		{
			$c = new Contact;
			$c->name = $i;
			$c->message = $i;
			$c->save();
		}

		$response = $this->fakeRequest('/test', 'GET', ['pageSize' => 5]);
		$obj = json_decode($response->getContent());

		$this->assertEquals(5, count($obj));
	}

	/**
	 * Test adding where clauses to the request
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_allWithFilter()
	{
		for ($i = 0; $i < 3; $i++)
		{
			$c = new Contact();
			$c->name = $i;
			$c->message = $i;
			$c->save();
		}

		$response = $this->fakeRequest('/test', 'GET', ['pageSize' => 5, 'name' => 2]);
		$obj = json_decode($response->getContent());

		$this->assertEquals(1, count($obj));
		//$this->assertEquals(2, $obj[0]->name);
	}


} // END class RestControllerTest extends TestCase
