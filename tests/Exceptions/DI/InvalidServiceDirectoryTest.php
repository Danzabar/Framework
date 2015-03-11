<?php

use Wasp\Exceptions\DI\InvalidServiceDirectory,
	Wasp\DI\DI;

/**
 * Test case for the invalid sevrice directory exception class
 *
 * @package Wasp
 * @subpackage Test\Exceptions
 * @author Dan Cox
 */
class InvalidServiceDirectoryTest extends \PHPUnit_Framework_TestCase
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

			throw new InvalidServiceDirectory(new DI);

		} catch (InvalidServiceDirectory $e) {

			$this->assertInstanceOf('Wasp\DI\DI', $e->getDI());
		}
	}


} // END class InvalidServiceDirectoryTest extends \PHPUnit_Framework_TestCase

