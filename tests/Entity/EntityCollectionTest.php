<?php

use Wasp\Test\TestCase;

/**
 * Test case for the EntityCollection class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class EntityCollectionTest extends TestCase
{

	/**
	 * An array of DI Compiler passes used
	 *
	 * @var Array
	 */
	protected $passes = [
		'Wasp\DI\Pass\DatabaseMockeryPass'
	];

	/**
	 * Set up test class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$this->DI->get('database')->create(ENTITIES);
	}

	/**
	 * Test deleting a bulk amount of records
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_bulkDelete()
	{
		for ($i = 0; $i < 10; $i++) {
			$ent = new \Wasp\Test\Entity\Entities\Test();
			$ent->name = "bob_" . $i;
			$ent->save();
		}

		$collection = \Wasp\Test\Entity\Entities\Test::db()->get();

		$collection->delete();

		$results = \Wasp\Test\Entity\Entities\Test::db()->get();

		$this->assertEquals(0, count($results));
	}

	/**
	 * Test updating a collection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_bulkUpdate()
	{
		for ($i = 0; $i < 10; $i++) {
			$ent = new \Wasp\Test\Entity\Entities\Test();
			$ent->name = "bob_" . $i;
			$ent->save();
		}

		$collection = \Wasp\Test\Entity\Entities\Test::db()->get();

		foreach ($collection as $key => $coll)
		{
			$coll->name = 'jeff_' . $key;
		}

		$collection->save();

		$result = \Wasp\Test\Entity\Entities\Test::db()->get(['name' => 'jeff_1']);

		$this->assertEquals(1, count($result));
	}

	/**
	 * Test convert to json
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_json()
	{
		$ent = new \Wasp\Test\Entity\Entities\Test();
		$ent->name = 'foo';
		$ent->save();

		$result = \Wasp\Test\Entity\Entities\Test::db()->get(['name' => 'foo']);

		$this->assertContains('"_id":1,"name":"foo"', $result->json());
	}
	
} // END class EntityCollectionTest extends TestCase
