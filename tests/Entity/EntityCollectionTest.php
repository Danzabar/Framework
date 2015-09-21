<?php

use Wasp\Test\TestCase;
use Wasp\Test\Entity\Entities\Test;

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
     * Set up test class
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        parent::setUp();

        $this->DI->get('database')->create(ENTITIES);
        $this->DI->get('database')->setEntity('Wasp\Test\Entity\Entities\Test');
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
            $ent = new Test;
            $ent->name = "bob_" . $i;
            $ent->save();
        }

        $collection = $this->DI->get('database')->get();
        $collection->delete();

        $results = $this->DI->get('database')->get();

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
            $ent = new Test;
            $ent->name = "bob_" . $i;
            $ent->save();
        }

        $collection = $this->DI->get('database')->get();

        foreach ($collection as $key => $coll)
        {
            $coll->name = 'jeff_' . $key;
        }

        $collection->save();

        $result = $this->DI->get('database')->get(['name' => 'jeff_1']);

        $this->assertEquals(1, count($result));
    }

} // END class EntityCollectionTest extends TestCase
