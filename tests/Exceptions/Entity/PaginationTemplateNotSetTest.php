<?php

use Wasp\Exceptions\Entity\PaginationTemplateNotSet;

/**
 * Test case for the pagination template not set exception
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class PaginationTemplateNotSetTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * fire exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fire()
	{
		try {
			
			throw new PaginationTemplateNotSet ();

		} catch (\Exception $e) {
			$this->assertEquals('The pagination template has not been set.', $e->getMessage());
			return;
		}

		$this->fail('Failed to throw error');
	}


} // END class PaginationTemplateNotSetTest extends \PHPUnit_Framework_TestCase

