<?php

use Wasp\Test\TestCase;

/**
 * Test case for the fixtures class
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class FixtureTest extends TestCase
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

        // add a connection
        $this->DI->get('connections')->add('func', [
            'driver'        => 'pdo_mysql',
            'user'          => 'user',
            'dbname'        => 'wasp',
            'models'        => ENTITIES
        ]);

        $this->DI->get('connection')->connect('func');
        $this->DI->get('database')->setEntity('Wasp\Test\Entity\Entities\Test');
    }

    /**
     * Tear down test env
     *
     * @return void
     * @author Dan Cox
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->DI->get('schema')->dropTables();
    }

    /**
     * Test importing fixtures
     *
     * @author Dan Cox
     */
    public function test_importFixturesPurgeFixtures()
    {
        $this->DI->get('schema')->create();

        $FM = $this->DI->get('fixtures');
        $FM->setDirectory(__DIR__ . '/Fixtures/');
        $FM->load();
        $FM->import();

        // We should have a jim.
        $result = $this->DI->get('database')->get();

        $this->assertEquals('jim', $result[0]->name);
    }

} // END class FixtureTest extends TestCase
