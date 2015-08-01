<?php

use Wasp\Test\TestCase;

/**
 * Functional tests, unmocked for the database
 *
 * @package Wasp
 * @subpackage Tests\Database
 * @author Dan Cox
 */
class DatabaseFunctionalTest extends TestCase
{

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$this->DI->get('connections')->add('func', Array(
			'driver'	=> 'pdo_mysql',
			'user'		=> 'user',
			'dbname'	=> 'wasp',
			'models'	=> ENTITIES
		));

		$this->DI->get('connection')->connect('func');
	}

	/**
	 * Remove all the tables added
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function tearDown()
	{
		$this->DI->get('schema')->dropTables();
	}

	/**
	 * Test building the Schema, Adding a row and querying it back out, a basic test
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildSchemaAddRowQuery()
	{
		// Create the Schema because we dont already have one
		$this->DI->get('schema')->create();

		// Add a row using the entity
		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->name = 'foo';
		$entity->save();

		// Query it back out using the entity class
		$result = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test')->findOneBy(['name' => 'foo']);

		$this->assertEquals('foo', $result->name);
		$this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->DI->get('database')->entityManager());
	}

	/**
	 * Update schema instead of create, because that should also work, and add a few records and retreive them back out in various ways.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_updateSchemaGetBulk()
	{
		$this->DI->get('schema')->update();

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->name = 'foo';
		$entity->save();

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->name = 'bar';
		$entity->save();

		$entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
		$entity->name = 'zim';
		$entity->save();

		$e = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');

		$results = $e->get();
		$results2 = $e->get(Array(), Array(), 2, 1);
		$results3 = $this->DI->get('database')->setEntity('Wasp\Test\Entity\Entities\Test')->findOneBy(['name' => 'bar']);
		$results4 = $e->get([], ['name' => 'desc']);

		// Assertions for result1
		$this->assertEquals(3, count($results));
		$this->assertEquals('foo', $results[0]->name);
		$this->assertEquals('bar', $results[1]->name);
		$this->assertEquals('zim', $results[2]->name);

		// Assertions for result2
		$this->assertEquals(2, count($results2));
		$this->assertEquals('bar', $results2[0]->name);

		// Assertions for result3
		$this->assertInstanceOf('Wasp\Test\Entity\Entities\Test', $results3);
		$this->assertEquals('bar', $results3->name);

		// Assertions for result4
		$this->assertEquals('zim', $results4[0]->name);
	}

	/**
	 * Test the raw query
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function test_RawQueries()
	{
		$this->DI->get('schema')->update();

		$query = $this->DI->get('database')
						  ->raw(sprintf(
						  		"INSERT INTO Test (`name`) VALUES ('%s')", 'Bob'
						  	));

		$select = $this->DI->get('database')->raw("SELECT * FROM Test", false);
		$select->execute();
		$results = $select->fetch();

		$this->assertEquals(1, $select->rowCount());
		$this->assertEquals('Bob', $results['name']);
	}

} // END class DatabaseFunctionalTest extends TestCase
