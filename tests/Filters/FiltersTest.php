<?php

use Wasp\Test\TestCase;

/**
 * Test case for the filters extensions
 *
 * @package Wasp
 * @subpackage Tests\Filters
 * @author Dan Cox
 */
class FiltersTest extends TestCase
{
		
	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$register = new Wasp\DI\ExtensionRegister;
		$register->loadFromArray(['Wasp\Test\Filters\FilterExtension\FilterExtension']);

		parent::setUp();
	}

	/**
	 * Test firing a filter
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fireFilter()
	{
		$filter = $this->DI->get('filter')->prepare();

		$result = $filter->fire('testfilter', 'beforetest');

		$this->assertEquals('before-route', $result);
	}

	/**
	 * Test that an exception is throw when we have an invalid exception
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_throwExceptionOnInvalidFilter()
	{
		$this->setExpectedException('Wasp\Exceptions\Filters\InvalidFilterService');

		$this->DI->get('filter')->prepare()->fire('example', 'example');
	}

} // END class FiltersTest extends TestCase
