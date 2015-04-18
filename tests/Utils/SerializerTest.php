<?php

use Wasp\Test\TestCase;

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
	 * Set up tests
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
	 * Test serializing an entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_serializingORMEntity()
	{	
		$test = new \Wasp\Test\Entity\Entities\Test();
		$test->name = 'foo';
		$test->save();

		$result = \Wasp\Test\Entity\Entities\Test::db()->findOneBy(['name' => 'foo']);

		$serializer = $this->DI->get('serializer');
		$json = $serializer->serialize($result, 'json');

		$this->assertEquals('{"_id":1,"name":"foo"}', $json);
	}


} // END class SerializerTest extends TestCase
