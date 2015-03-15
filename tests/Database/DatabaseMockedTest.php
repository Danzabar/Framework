<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery;

/**
 * Test Case for the Database Class
 *
 * @package Wasp
 * @subpackage Tests\Database
 * @author Dan Cox
 */
class DatabaseMockedTest extends TestCase
{
	/**
	 * Setup test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		// Create the Service Mockerys before running the parent setup
		$database = new ServiceMockery('connection');
		$database->add();

		parent::setUp();
	}

	/**
	 * Test a mocked connection class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_connection()
	{
		$connection = $this->DI->get('connection');
		$database = $this->DI->get('database');

		$connection->shouldReceive('connect')->andReturn(TRUE);

		$this->assertTrue($database->connection->connect('wasp'));	
	}

	/**
	 * Test getting and setting the entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getsetEntity()
	{
		$database = $this->DI->get('database');

		$database->setEntity('Wasp\Entity\Entity');

		$this->assertEquals('Wasp\Entity\Entity', $database->getEntity());
	}

	/**
	 * Test the database find functionality, mocked ofcourse
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_databaseFind()
	{
		$returnStub = new STDClass;
		$returnStub->foo = 'bar';

		$connection = $this->DI->get('connection');
		$connection->shouldReceive('connection')->andReturn($connection);
		$connection->shouldReceive('find')->with('Test', 1)->andReturn($returnStub);

		$result = $this->DI->get('database')
						   ->setEntity('Test')
						   ->find(1);

		$this->assertEquals($returnStub, $result);
		$this->assertEquals('bar', $result->foo);
	}	
	
} // END class DatabaseMockedTest extends TestCase
