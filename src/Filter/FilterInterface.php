<?php namespace Wasp\Filter;

/**
 * Filter interface
 *
 * @package Wasp
 * @subpackage Filter
 * @author Dan Cox
 */
Interface FilterInterface
{

	/**
	 * Run the filter
	 *
	 * @param Object $event
	 * @return void
	 * @author Dan Cox
	 */
	public function filter($event);

}
