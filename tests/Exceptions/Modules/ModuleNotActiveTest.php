<?php

use Wasp\Exceptions\Modules\ModuleNotActive;

/**
 * Test case for the exception module not active
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class ModuleNotActiveTest extends \PHPUnit_Framework_TestCase
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
			throw new ModuleNotActive('Test');
		} catch (\Exception $e) {
			$this->assertEquals('Test', $e->getModule());
		}
	}
	
} // END class ModuleNotActiveTest extends \PHPUnit_Framework_TestCase
