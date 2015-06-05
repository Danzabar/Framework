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

		$response = $this->DI->get('router')->resolve('/test/1');

		$obj = json_decode($response->getContent());

		$this->assertEquals('Test', $obj->name);
	}

} // END class RestControllerTest extends TestCase
