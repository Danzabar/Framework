<?php

use Wasp\Exceptions\Entity\InvalidTriggerEvent;

/**
 * Test Case for the Invalid trigger event exception
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class InvalidTriggerEventTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Test firing the exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fire()
	{
		try {
		
			throw new InvalidTriggerEvent('Test', ['foo', 'bar']);

		} catch (\Exception $e) {
			
			$this->assertEquals(['foo', 'bar'], $e->getAllowedEvents());
			$this->assertEquals('Test', $e->getEvent());
			return;
		}

		$this->fail('Exception did not trigger');
	}

} // END class InvalidTriggerEventTest extends \PHPUnit_Framework_TestCase
