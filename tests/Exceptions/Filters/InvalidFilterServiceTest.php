<?php

use Wasp\Exceptions\Filters\InvalidFilterService;

/**
 * Test case for the InvalidFilterService exception class
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class InvalidFilterServiceTest extends \PHPUnit_Framework_TestCase
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
			throw new InvalidFilterService('Test');

		} catch (\Exception $e) {

			$this->assertEquals('Test', $e->getService());
		}
	}
	
} // END class InvalidFilterServiceTest extends \PHPUnit_Framework_TestCase
