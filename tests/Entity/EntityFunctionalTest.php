<?php

use Wasp\Test\TestCase,
    Wasp\Test\Entity\Entities\Test;

/**
 * Functional test for entity methods
 *
 * @package Wasp
 * @subpackage Tests\Entity
 * @author Dan Cox
 */
class EntityFunctionalTest extends TestCase
{

    /**
     * An array of compiler passes used in this test
     *
     * @var Array
     */
    protected $passes = [
        'Wasp\DI\Pass\DatabaseMockeryPass'
    ];

    /**
     * Set up test
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
     * Test updating an object with an array
     *
     * @return void
     * @author Dan Cox
     */
    public function test_updatingWithArray()
    {
        $entity = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Test');
        $entity->updateFromArray(['name' => 'bob']);
        $entity->save();

        $res = $this->DI->get('entity')
                    ->load('Wasp\Test\Entity\Entities\Test')
                    ->findOneBy(['name' => 'bob']);

        $this->assertEquals('bob', $res->name);
    }


} // END class EntityFunctionalTest extends TestCase
