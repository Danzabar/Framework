<?php

use Wasp\Test\TestCase;
use Wasp\Test\Entity\Entities\Test;

/**
 * Functional tests, unmocked for the database
 *
 * @package Wasp
 * @subpackage Tests\Database
 * @author Dan Cox
 */
class DatabaseFunctionalTest extends TestCase
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

        $this->DI->get('connections')->add('func', Array(
            'driver'    => 'pdo_mysql',
            'user'      => 'user',
            'dbname'    => 'wasp',
            'models'    => ENTITIES
        ));

        $this->DI->get('connection')->connect('func');

        $this->DI->get('database')->setEntity('Wasp\Test\Entity\Entities\Test');
    }

    /**
     * Remove all the tables added
     *
     * @return void
     * @author Dan Cox
     */
    public function tearDown()
    {
        $this->DI->get('schema')->dropTables();
    }

    /**
     * Test building the Schema, Adding a row and querying it back out, a basic test
     *
     * @return void
     * @author Dan Cox
     */
    public function test_buildSchemaAddRowQuery()
    {
        // Create the Schema because we dont already have one
        $this->DI->get('schema')->create();

        // Add a row using the entity
        $entity = new Test;
        $entity->name = 'foo';
        $entity->save();

        // Query it back out using the entity class
        $result = $this->DI->get('database')->findOneBy(['name' => 'foo']);

        $this->assertEquals('foo', $result->name);
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->DI->get('database')->entityManager());
    }

    /**
     * Update schema instead of create, because that should also work, and add a few records and retreive them back out in various ways.
     *
     * @return void
     * @author Dan Cox
     */
    public function test_updateSchemaGetBulk()
    {
        $this->DI->get('schema')->update();

        $entity = new Test;
        $entity->name = 'foo';
        $entity->save();

        $entity = new Test;
        $entity->name = 'bar';
        $entity->save();

        $entity = new Test;
        $entity->name = 'zim';
        $entity->save();

        $results = $this->DI->get('database')->get();
        $results2 = $this->DI->get('database')->get(Array(), Array(), 2, 1);
        $results3 = $this->DI->get('database')->findOneBy(['name' => 'bar']);
        $results4 = $this->DI->get('database')->get([], ['name' => 'desc']);

        // Assertions for result1
        $this->assertEquals(3, count($results));
        $this->assertEquals('foo', $results[0]->name);
        $this->assertEquals('bar', $results[1]->name);
        $this->assertEquals('zim', $results[2]->name);

        // Assertions for result2
        $this->assertEquals(2, count($results2));
        $this->assertEquals('bar', $results2[0]->name);

        // Assertions for result3
        $this->assertInstanceOf('Wasp\Test\Entity\Entities\Test', $results3);
        $this->assertEquals('bar', $results3->name);

        // Assertions for result4
        $this->assertEquals('zim', $results4[0]->name);
    }

    /**
     * Test the raw query
     *
     * @return void
     * @author Dan Cox
     **/
    public function test_RawQueries()
    {
        $this->DI->get('schema')->update();

        $query = $this->DI->get('database')
                          ->raw(sprintf(
                                "INSERT INTO Test (`name`) VALUES ('%s')", 'Bob'
                            ));

        $select = $this->DI->get('database')->raw("SELECT * FROM Test", false);
        $select->execute();
        $results = $select->fetch();

        $this->assertEquals(1, $select->rowCount());
        $this->assertEquals('Bob', $results['name']);
    }

    /**
     * Test the find or fail method throws an exception with no results
     *
     * @return void
     * @author Dan Cox
     */
    public function test_find_or_fail()
    {
        $this->setExpectedException('Wasp\Exceptions\Entity\RecordNotFound');

        $this->DI->get('schema')->update();

        $this->DI->get('database')
                 ->findOrFail(['name' => 'BatMan']);
    }

    /**
     * Test a successful find or fail
     *
     * @return void
     * @author Dan Cox
     */
    public function test_find_or_fail_success()
    {
        $this->DI->get('schema')->update();

        $entity = new Test;
        $entity->name = 'bob';
        $entity->save();

        $result = $this->DI->get('database')->findOrFail(['name' => 'bob']);

        $this->assertInstanceOf('Wasp\Test\Entity\Entities\Test', $result);
    }

    /**
     * Test the bulk remove functionality
     *
     * @return void
     * @author Dan Cox
     */
    public function test_bulk_remove()
    {
        $this->DI->get('schema')->update();

        $entities = new Wasp\Utils\Collection;

        $row1 = new Test;
        $row1->name = 'bob';

        $row2 = new Test;
        $row2->name = 'test';

        $row3 = new Test;
        $row3->name = 'foo';

        $entities->addAll([$row1, $row2, $row3]);

        $this->DI->get('database')->saveAll($entities);

        $collection = $this->DI->get('database')->get();

        $this->assertEquals(3, count($collection));

        $this->DI->get('database')->removeAll($collection);

        $col2 = $this->DI->get('database')->get();

        $this->assertEquals(0, count($col2));
    }

} // END class DatabaseFunctionalTest extends TestCase
