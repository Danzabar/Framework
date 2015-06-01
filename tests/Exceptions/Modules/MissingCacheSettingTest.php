<?php

use Wasp\Exceptions\Modules\MissingCacheSettings;

/**
 * Test Case for the exception missing cache settings
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class MissingCacheSettingTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Test firing exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fireException()
	{
		try {
			throw new MissingCacheSettings('Test');
		} catch (\Exception $e) {
			$this->assertEquals('Test', $e->getSetting());
		}
	}
	
} // END class MissingCacheSettingTest extends \PHPUnit_Framework_TestCase
