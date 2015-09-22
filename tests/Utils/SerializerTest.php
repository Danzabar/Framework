<?php

use Wasp\Test\TestCase;
use Wasp\Test\Entity\Entities\Test;

/**
 * Test case for the serializer class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class SerializerTest extends TestCase
{

    /**
     * A list of DI Compiler Passes
     *
     * @var Array
     */
    protected $passes = [
        'Wasp\DI\Pass\DatabaseMockeryPass'
    ];

    /**
     * Instance of the test entity
     *
     * @var Wasp\Test\Entity\Entities\Test
     */
    protected $entity;

    /**
     * Set up tests
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
     * Test creating the serializer object
     *
     * @return void
     * @author Dan Cox
     */
    public function test_createSerializer()
    {
        $serializer = $this->DI->get('serializer');
        $serializer->config(__DIR__ . '/SerializerCache', TRUE);
    }

    /**
     * Test serializing an entity
     *
     * @return void
     * @author Dan Cox
     */
    public function test_serializingORMEntity()
    {
        $test = new Test;
        $test->name = 'foo';
        $test->save();

        $result = $this->DI->get('database')->findOneBy(['name' => 'foo']);

        $serializer = $this->DI->get('serializer');
        $json = $serializer->serialize($result, 'json');

        $this->assertContains('"_id":1,"name":"foo"', $json);
    }

    /**
     * Test the entities json method
     *
     * @return void
     * @author Dan Cox
     */
    public function test_serializeThroughEntity()
    {
        $test = new Test;
        $test->name = 'foo';
        $test->save();

        $result = $this->DI->get('database')->findOneBy(['name' => 'foo']);

        $json = $result->toJSON();

        $this->assertContains('"_id":1,"name":"foo"', $json);
    }
} // END class SerializerTest extends TestCase
