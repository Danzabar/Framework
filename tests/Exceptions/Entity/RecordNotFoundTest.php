<?php

use Wasp\Exceptions\Entity\RecordNotFound;

/**
 * Test case for record not found exception
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class RecordNotFoundTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test firing exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fire()
	{
		try {

			throw new RecordNotFound;

		} catch (\Exception $e) {
			// No need to assert anything here.
			return;
		}

		$this->fail('Exception not caught');
	}

} // END class RecordNotFoundTest extends \PHPUnit_Framework_TestCase
