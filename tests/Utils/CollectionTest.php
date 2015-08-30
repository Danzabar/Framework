<?php

use Wasp\Utils\Collection,
    Wasp\Test\TestCase;

/**
 * Test Case for the collection class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class CollectionTest extends TestCase
{

    /**
     * Collectable array
     *
     * @var Array
     */
    protected $collectable;
    
    /**
     * Set up test scenario
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        parent::setUp();

        $object = new StdClass();
        $object->test = 'var';

        $this->collectable = Array(
            'test'      => 'var',
            'foo'       => Array('bar', 'zim'),
            'testObj'   => $object
        );
    }

    /**
     * Test that we can iterate properly through a collection 
     *
     * @return void
     * @author Dan Cox
     */
    public function test_iterableFunctionality()
    {
        $collection = new Collection($this->collectable);

        $count = 0;
        $keys = Array();
        
        foreach($collection as $key => $var)
        {
            $count++;
            $keys[] = $key;
        }

        $this->assertEquals(3, $count);
        $this->assertEquals(Array('test', 'foo', 'testObj'), $keys);
    }

    /**
     * Test array access functionality
     *
     * @return void
     * @author Dan Cox
     */
    public function test_arrayAccess()
    {
        $collection = new Collection($this->collectable);
        unset($collection['testObj']);

        $collection['testing'] = 'var';
        $collection[] = 'foobar';

        $this->assertEquals('var', $collection['test']);
        $this->assertFalse(isset($collection['testObj']));
        $this->assertEquals(4, count($collection));
    }

    /**
     * Testing basic functionality
     *
     * @return void
     * @author Dan Cox
     */
    public function test_basicHelperTools()
    {
        $collection = new Collection($this->collectable);
        $collection->remove('testObj');

        $this->assertEquals('var', $collection->get('test'));
        $this->assertFalse(array_key_exists('testObj', $collection));
        $this->assertEquals(NULL, $collection->get('fake'));
    }

    /**
     * Test the json conversion
     *
     * @return void
     * @author Dan Cox
     */
    public function test_json()
    {
        $collection = new Collection($this->collectable);

        $json = $collection->json();

        $this->assertEquals(json_encode($this->collectable), $json);
    }

    /**
     * Test adding a new record through the "Add" method
     *
     * @return void
     * @author Dan Cox
     */
    public function test_add()
    {
        $collection = new Collection(Array('foo' => 'bar'));

        $collection->add('key', 'value');

        $this->assertTrue(isset($collection['key']));
    }

    /**
     * Test appending data
     *
     * @return void
     * @author Dan Cox
     */
    public function test_append()
    {
        $collection = new Collection(['foo' => 'bar']);

        $collection->append(['test' => 'test']);

        $this->assertEquals(
            ['foo' => 'bar', 'test' => 'test'],
            $collection->all()
        );
    }

    /**
     * Test prepending dat
     *
     * @return void
     * @author Dan Cox
     */
    public function test_prepend()
    {
        $collection = new Collection(['foo' => 'bar']);

        $collection->prepend(['test' => 'test']);

        $this->assertEquals(
            ['test' => 'test', 'foo' => 'bar'],
            $collection->all()
        );
    }

} // END class CollectionTest extends TestCase
