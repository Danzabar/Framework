<?php

use Wasp\Test\Entity\Entities\Test,
	Wasp\DI\ServiceMockery,
	Wasp\Test\TestCase;

/**
 * Mocked test case for the Entity class
 *
 * @package Wasp
 * @subpackage Tests\Entity
 * @author Dan Cox
 */
class EntityMockTest extends TestCase
{

	/**
	 * Setup test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$dbMock = new ServiceMockery('database');
		$dbMock->add();

		parent::setUp();
	}
	
	/**
	 * Test getting the database through the entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getDatabase()
	{
		$this->DI->get('database')
				 ->shouldReceive('setEntity')
				 ->with('Wasp\Test\Entity\Entities\Test')
				 ->andReturn($this->DI->get('database'));

		// This will be a static function	
		// The result will also be a service decorator as its mocked.
		$this->assertInstanceOf('Wasp\DI\ServiceMockeryDecorator', Test::db());
	}

	/**
	 * Test the results of a query.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getFilledEntity()
	{
		// Coincidently, this will test the getters and setters of an entity.
		$entity = new Test;
		$entity->Id = 1;
		$entity->name = 'foo';

		$db = $this->DI->get('database');
		$db->shouldReceive('setEntity')->with('Wasp\Test\Entity\Entities\Test')->andReturn($db);
		$db->shouldReceive('find')->with(1)->andReturn($entity);

		$result = Test::db()->find(1);

		$this->assertEquals(1, $result->Id);
		$this->assertEquals('foo', $result->name);
	}
	
} // END class EntityMockTest extends TestCase
