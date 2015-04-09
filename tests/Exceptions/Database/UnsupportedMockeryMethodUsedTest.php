<?php

use Wasp\Exceptions\Database\UnsupportedMockeryMethodUsed;

/**
 * Test case for the Exception class unsupported mockery method used
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class UnsupportedMockeryMethodUsedTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Fire exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fire()
	{
		try {
			throw new UnsupportedMockeryMethodUsed('Test');
		} catch (\Exception $e) {
			$this->assertEquals('Test', $e->getMethod());
		}
	}

} // END class UnsupportedMockeryMethodUsed extends \PHPUnit_Framework_TestCase
