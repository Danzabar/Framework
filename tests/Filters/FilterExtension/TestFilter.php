<?php namespace Wasp\Test\Filters\FilterExtension;

/**
 * A test filter
 *
 * @package Wasp
 * @subpackage Tests\Filters
 * @author Dan Cox
 */
class TestFilter
{

	/**
	 * A test before method
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function beforeTest($route, $request)
	{
		return 'before-route';	
	}

} // END class TestFilter
