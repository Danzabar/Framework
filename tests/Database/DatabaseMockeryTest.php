<?php

use Wasp\Test\TestCase,
	Wasp\DI\Pass\DatabaseMockeryPass,
	Wasp\Test\Entity\Entities\Test,
	Wasp\Database\DatabaseMockery;

/**
 * Test case for the database mockery test
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class DatabaseMockeryTest extends TestCase
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	protected $passes = [
		'Wasp\DI\Pass\DatabaseMockeryPass'
	];

	/**
	 * Set up the Connection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * Check that the database class is now an instance of the mockery
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_databaseClassIsAnInstanceOfMockery()
	{
		$this->assertInstanceOf('Wasp\Database\DatabaseMockery', $this->DI->get('database'));
	}

	/**
	 * Test the mocked connection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_connectionToMock()
	{
		$this->DI->get('database')->create(ENTITIES);

		$test = new Test();
		$test->name = 'bob';
		$test->save();

		// We should be able to query out bob now
		$results = Test::db()->findOneBy(['name' => 'bob']);

		$this->assertEquals('bob', $results->name);
	}


	
} // END class DatabaseMockeryTest extends TestCase
