<?php

use Wasp\Exceptions\Forms\IncorrectFieldType;

/**
 * Test Case for the incorrect field type exception
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class IncorrectFieldTypeTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Test firing the exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fireException()
	{
		try {
			throw new IncorrectFieldType('Test');
		} catch (\Exception $e) {
			$this->assertEquals('Test', $e->getType());
		}
	}

} // END class IncorrectFieldTypeTest extends \PHPUnit_Framework_TestCase
