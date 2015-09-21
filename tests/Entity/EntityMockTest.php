<?php

use Wasp\DI\ServiceMockery,
    Wasp\Test\Entity\Entities\Test,
    Wasp\Test\TestCase;

/**
 * Mocked test case for the Entity class
 *
 * @package Wasp
 * @subpackage Tests\Entity
 * @author Dan Cox
 */
class EntityMockTest extends TestCase
{
    /**
     * Setup test env
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        $dbMock = new ServiceMockery('database');
        $dbMock->add();

        parent::setUp();
    }

    /**
     * Test a mocked save on an entity
     *
     * @return void
     * @author Dan Cox
     */
    public function test_save()
    {
        $db = $this->DI->get('database');
        $db->shouldReceive('save')->once();

        $entity = new Test;
        $entity->name = 'foo';
        $entity->save();
    }

    /**
     * Test deleting a entity
     *
     * @return void
     * @author Dan Cox
     */
    public function test_delete()
    {
        $db = $this->DI->get('database');
        $db->shouldReceive('remove')->once();

        $entity = new Test;
        $entity->Id = 1;
        $entity->name = 'test';
        $entity->delete();
    }

    /**
     * Test setting an invalid key
     *
     * @return void
     * @author Dan Cox
     */
    public function test_accessToInvalidKeySet()
    {
        $this->setExpectedException('Wasp\Exceptions\Entity\AccessToInvalidKey');

        $entity = new Test;
        $entity->id = 1;
    }

    /**
     * Test getting an invalid key
     *
     * @return void
     * @author Dan Cox
     */
    public function test_accessToInvalidKeyGet()
    {
        $this->setExpectedException('Wasp\Exceptions\Entity\AccessToInvalidKey');

        $entity = new Test;
        $entity->id;
    }

    /**
     * Test setting entity values from an array
     *
     * @return void
     * @author Dan Cox
     */
    public function test_settingValuesFromArray()
    {
        $db = $this->DI->get('database');
        $db->shouldReceive('save')->once();

        $entity = new Test;
        $entity->updateFromArray(['name' => 'bob']);
        $entity->save();
    }

} // END class EntityMockTest extends TestCase
