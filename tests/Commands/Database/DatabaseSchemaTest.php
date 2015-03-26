<?php

use Wasp\Test\TestCase,
	Symfony\Component\Console\Tester\CommandTester,
	Wasp\DI\ServiceMockery;

/**
 * Test case for the database schema command class
 *
 * @package Wasp
 * @subpackage Tests\Commands
 * @author Dan Cox
 */
class DatabaseSchemaTest extends TestCase
{

	/**
	 * Array of commands used by the test
	 *
	 * @var Array
	 */
	protected $commands = [
		'Wasp\Commands\Database\DatabaseSchema'
	];

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$mock = new ServiceMockery('schema');
		$mock->add();

		parent::setUp();
	}

	/**
	 * Test building the schema
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildSchema()
	{
		$schema = $this->DI->get('schema');
		$command = $this->DI->get('console')->find('database:schema');

		$schema->shouldReceive('update')->once();

		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName()
		]);

		$this->assertContains('built schema', $CT->getDisplay());
	}

	/**
	 * Test dropping all tables
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dropAllTables()
	{
		$schema = $this->DI->get('schema');
		$command = $this->DI->get('console')->find('database:schema');

		$schema->shouldReceive('dropTables')->once();

		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName(),
			'--remove'		=> true
		]);
		
		$this->assertContains('dropped all tables', $CT->getDisplay());
	}

	/**
	 * Test dropping a single entity table
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dropSingleTable()
	{
		$schema = $this->DI->get('schema');
		$command = $this->DI->get('console')->find('database:schema');

		$schema->shouldReceive('dropTable')->with('Wasp\Entity\Test')->once();

		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName(),
			'entity'		=> 'Wasp\Entity\Test',
			'--remove'		=> true
		]);
		
		$this->assertContains('Wasp\Entity\Test table', $CT->getDisplay());
	}

} // END class DatabaseSchemaTest extends TestCase
