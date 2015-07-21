<?php

use Wasp\Test\TestCase,
	Wasp\Test\Entity\Entities\Test;

/**
 * Test case for the entity traits
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class TraitTest extends TestCase
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	protected $passes = [
		'Wasp\DI\Pass\DatabaseMockeryPass'
	];


	/**
	 * Set up test env
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
	 * Test the dateStamp trait
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_dateStamp()
	{
		$test = new Test;
		$test->name = 'test_dateStamp';
		$test->save();

		$this->assertInstanceOf('DateTime', $test->createdAt);
		$this->assertInstanceOf('DateTime', $test->updatedAt);

		$test->name = 'test_dateStamp2';
		$test->createdAt = $test->createdAt->modify('-4 days');
		$test->updatedAt = $test->updatedAt->modify('-4 days');
		$test->save();

		$this->assertTrue($test->createdAt->getTimestamp() < $test->updatedAt->getTimestamp());
	}

	/**
	 * Test the slug trait
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_slugify()
	{
		$test = new Test;
		$test->name = 'slug_test';
		$test->title = 'Test Title';
		$test->save();

		$this->assertEquals('test-title', $test->slug);

		$test->name = 'slug_test_2';
		$test->save();

		$this->assertEquals('test-title', $test->slug);

		$test->title = 'New Title';
		$test->save();

		$this->assertEquals('new-title', $test->slug);
	}

	/**
	 * Test the suspendable trait
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_suspension()
	{
		$test = new Test;
		$test->name = 'test_suspension';
		$test->save();

		$this->assertEquals(null, $test->suspendedFrom);
		$this->assertEquals(null, $test->suspendedUntil);

		$test->suspend(['+4 days', '+2 months']);

		$this->assertInstanceOf('DateTime', $test->suspendedFrom);
		$this->assertInstanceof('DateTime', $test->suspendedUntil);
	}

	
} // END class TraitTest extends TestCase
