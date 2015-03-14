<?php

use Wasp\DI\ServiceMockeryDecorator;

/**
 * Just a basic functionality test
 *
 * @package Wasp
 * @subpackage Tests\DI
 * @author Dan Cox
 */
class ServiceMockeryDecoratorTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Just a basic test
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_basic()
	{
		$mock = new ServiceMockeryDecorator('database');

		$mock->foo = 'bar';
		$mock->shouldReceive('test')->andReturn('testing');

		$this->assertEquals('bar', $mock->foo);
		$this->assertEquals('testing', $mock->test());
	}
	
} // END class ServiceMockeryDecoratorTest extends \PHPUnit_Framework_TestCase
