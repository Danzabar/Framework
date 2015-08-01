<?php

use	Wasp\DI\ServiceMockery,
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
	 * Test the results of a query.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getFilledEntity()
	{
		// Coincidently, this will test the getters and setters of an entity.
		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->Id = 1;
		$entity->name = 'foo';

		$db = $this->DI->get('database');
		$db->shouldReceive('setEntity')->with('Wasp\Test\Entity\Entities\Test')->andReturn($db);
		$db->shouldReceive('find')->with(1)->andReturn($entity);

		$result = $this->DI->get('entity')
					   ->load('Wasp\Test\Entity\Entities\Test')
					   ->find(1);

		$this->assertEquals(1, $result->Id);
		$this->assertEquals('foo', $result->name);
	}

	/**
	 * Test a mocked save on an entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_save()
	{
		$db = $this->DI->get('database');
		$db->shouldReceive('save')->once();

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->name = 'foo';
		$entity->save();
	}

	/**
	 * Test deleting a entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_delete()
	{
		$db = $this->DI->get('database');
		$db->shouldReceive('remove')->once();

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->Id = 1;
		$entity->name = 'test';
		$entity->delete();
	}

	/**
	 * Test setting an invalid key
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_accessToInvalidKeySet()
	{
		$this->setExpectedException('Wasp\Exceptions\Entity\AccessToInvalidKey');

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->id = 1;
	}

	/**
	 * Test getting an invalid key
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_accessToInvalidKeyGet()
	{
		$this->setExpectedException('Wasp\Exceptions\Entity\AccessToInvalidKey');

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->id;
	}

	/**
	 * Test setting entity values from an array
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_settingValuesFromArray()
	{
		$db = $this->DI->get('database');
		$db->shouldReceive('save')->once();

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->updateFromArray(['name' => 'bob']);
		$entity->save();
	}

} // END class EntityMockTest extends TestCase
