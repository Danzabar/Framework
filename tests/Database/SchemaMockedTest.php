<?php

use Wasp\Test\TestCase,
	\Mockery as m,
	Wasp\DI\ServiceMockery;

/**
 * Test case for the Schema class
 *
 * @package Wasp
 * @subpackage Tests\Database
 * @author Dan Cox
 */
class SchemaMockedTest extends TestCase
{

	/**
	 * Schema Mock
	 *
	 * @var Object
	 */
	protected $schema;

	/**
	 * Metadata Factory Mock
	 *
	 * @var Object
	 */
	protected $metadataFactory;

	/**
	 * An array of service mocks to register
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
		$this->schema = m::mock('schema');
		$this->metadataFactory = m::mock('metadataFactory');

		parent::setUp();
	}

	/**
	 * Test updating the schema
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_updateSchema()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('getSchemaTool')->once()->andReturn($this->schema);
		$connection->shouldReceive('connection')->once()->andReturn($connection);
		$connection->shouldReceive('getMetadataFactory')->once()->andReturn($this->metadataFactory);

		$this->metadataFactory->shouldReceive('getAllMetadata')->once()->andReturn(Array());
		$this->schema->shouldReceive('updateSchema')->once();

		$schema = $this->DI->get('schema');
		$schema->update();
	}

	/**
	 * Drop database
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dropDatabase()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('getSchemaTool')->once()->andReturn($this->schema);
		$connection->shouldReceive('connection')->once()->andReturn($connection);
		$connection->shouldReceive('getMetadataFactory')->once()->andReturn($this->metadataFactory);

		$this->schema->shouldReceive('dropDatabase')->once();

		$this->DI->get('schema')->dropDatabase();
	}

	/**
	 * Create the schemas
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_create()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('getSchemaTool')->once()->andReturn($this->schema);
		$connection->shouldReceive('connection')->once()->andReturn($connection);
		$connection->shouldReceive('getMetadataFactory')->once()->andReturn($this->metadataFactory);

		$this->metadataFactory->shouldReceive('getAllMetadata')->once()->andReturn(Array());
		$this->schema->shouldReceive('createSchema')->once();

		$this->DI->get('schema')->create();
	}

	/**
	 * Test dropping a table
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_DropTable()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('getSchemaTool')->once()->andReturn($this->schema);
		$connection->shouldReceive('connection')->once()->andReturn($connection);
		$connection->shouldReceive('getMetadataFactory')->once()->andReturn($this->metadataFactory);
		
		$this->schema->shouldReceive('dropSchema')->once()->with('Wasp\Test\Entity\Entities\Test');

		$this->DI->get('schema')->dropTable('Wasp\Test\Entity\Entities\Test');
	}
	
	/**
	 * Get SQL from an update
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_getSql()
	{
		$connection = $this->DI->get('connection');
		$connection->shouldReceive('getSchemaTool')->once()->andReturn($this->schema);
		$connection->shouldReceive('connection')->once()->andReturn($connection);
		$connection->shouldReceive('getMetadataFactory')->once()->andReturn($this->metadataFactory);

		$this->metadataFactory->shouldReceive('getAllMetadata')->once()->andReturn(Array());
		$this->schema->shouldReceive('getUpdateSchemaSql')->once();

		$this->DI->get('schema')->getSql();

	}

	
} // END class SchemaMockedTest extends TestCase
