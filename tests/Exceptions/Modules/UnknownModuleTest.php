<?php

use Wasp\Exceptions\Modules\UnknownModule;


/**
 * Test case for the Unknown module exception class
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class UnknownModuleTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Test fire the exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fire()
	{
		try {

			throw new UnknownModule('Test');
			
		} catch (\Exception $e) {
			$this->assertEquals('Test', $e->getModule());
		}
	}
	
} // END class UnknownModuleTest extends \PHPUnit_Framework_TestCase
