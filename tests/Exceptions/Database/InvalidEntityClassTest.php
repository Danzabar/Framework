<?php

use Wasp\Exceptions\Database\InvalidEntityClass;

/**
 * Test Case for the invalid entity class exception
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class InvalidEntityClassTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Fire the exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fireException()
	{
		try {
			throw new InvalidEntityClass(new Library);
		} catch (\Exception $e) {
			$this->assertEquals('Library', $e->getEntityName());
			$this->assertEquals(new Library, $e->getEntity());
		}
	}

} // END class InvalidEntityClassTest extends \PHPUnit_Framework_TestCase
