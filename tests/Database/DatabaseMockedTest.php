<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery,
	\Mockery as m;

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
	 * A mock of an entity
	 *
	 * @var Object
	 */
	protected $returnStub;

	/**
	 * A stubbed query builder
	 *
	 * @var Object
	 */
	protected $builderStub;

	/**
	 * An array of mocks used in this test
	 *
	 * @var Array
	 */
	protected $mocks = [
		'connection'
	];

	/**
	 * Setup test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		// Create the Service Mockerys before running the parent setup
		$this->returnStub = new STDClass;
		$this->returnStub->foo = 'bar';

		$this->builderStub = m::mock('queryBuilder');

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
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('connection')->andReturn($connection);
		$connection->shouldReceive('find')->with('Test', 1)->andReturn($this->returnStub);

		$result = $this->DI->get('database')
						   ->setEntity('Test')
						   ->find(1);

		$this->assertEquals($this->returnStub, $result);
		$this->assertEquals('bar', $result->foo);
	}	

	/**
	 * Test the find one by function with a mock
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_databaseFindOneBy()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('connection')->andReturn($connection);
		$connection->shouldReceive('getRepository')->with('Test')->andReturn($connection);
		$connection->shouldReceive('findOneBy')->with(Array('test' => 'value'), Array(), NULL, NULL)->andReturn($this->returnStub);

		$result = $this->DI->get('database')
						   ->setEntity('Test')
						   ->findOneBy(['test' => 'value']);
		
		$this->assertEquals($this->returnStub, $result);
		$this->assertEquals('bar', $result->foo);
	}
	
	/**
	 * Test the get function mock
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_databaseGet()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('connection')->andReturn($connection);
		$connection->shouldReceive('getRepository')->with('Test')->andReturn($connection);
		$connection->shouldReceive('findBy')->with(Array(), Array(), NULL, NULL)->andReturn([$this->returnStub]);

		$results = $this->DI->get('database')
							->setEntity('Test')
							->get();

		$this->assertInstanceOf('Wasp\Entity\EntityCollection', $results);
		$this->assertEquals('bar', $results[0]->foo);
	}

	/**
	 * Test the query builder function
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_queryBuilder()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('connection')->andReturn($connection);
		$connection->shouldReceive('createQueryBuilder')->andReturn($this->builderStub);
		$this->builderStub->shouldReceive('from')->with('Test', 'u')->andReturn($this->builderStub);
		$this->builderStub->query = 'foo';

		$builder = $this->DI->get('database')
							->setEntity('Test')
							->queryBuilder();

		$this->assertEquals('foo', $builder->query);
	}

} // END class DatabaseMockedTest extends TestCase
