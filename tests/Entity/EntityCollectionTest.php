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
        'Wasp\DI\Pass\DatabaseMockeryPass',
    ];

    /**
     * Registered extensions
     *
     * @var Array
     */
    protected $extensions = [
        'Wasp\Test\DI\Extension\EntityExtension'
    ];

    /**
     * Instance of the test entity
     *
     * @var \Wasp\Test\Entity\Entities\Test
     */
    protected $test;

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
            $ent = $this->DI->get('entity.test');
            $ent->name = "bob_" . $i;
            $ent->save();
        }

        $collection = $this->DI->get('entity.test')->get();
        $collection->delete();

        $results = $this->DI->get('entity.test')->get();

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
            $ent = $this->DI->get('entity.test');
            $ent->name = "bob_" . $i;
            $ent->save();
        }

        $collection = $this->DI->get('entity.test')->get();

        foreach ($collection as $key => $coll)
        {
            $coll->name = 'jeff_' . $key;
        }

        $collection->save();

        $result = $this->DI->get('entity.test')->get(['name' => 'jeff_1']);

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
        $ent = $this->DI->get('entity.test');
        $ent->name = 'foo';
        $ent->save();

        $result = $this->DI->get('entity.test')->get(['name' => 'foo']);

        $this->assertContains('"_id":1,"name":"foo"', $result->json());
    }

} // END class EntityCollectionTest extends TestCase
