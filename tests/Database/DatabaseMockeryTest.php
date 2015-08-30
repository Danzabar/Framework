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
     * Register Compiler passes
     *
     * @var Array
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

        $this->DI->get('database')->create(ENTITIES);
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
        $test = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
        $test->name = 'bob';
        $test->save();

        // We should be able to query out bob now
        $results = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test')->findOneBy(['name' => 'bob']);

        $this->assertEquals('bob', $results->name);
    }

    /**
     * Test clearing the database
     *
     * @return void
     * @author Dan Cox
     */
    public function test_clearingDatabase()
    {
        $test = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
        $test->name = 'dave';
        $test->save();

        // Clear the db
        $this->DI->get('database')->clearMockedData();
    }

} // END class DatabaseMockeryTest extends TestCase
