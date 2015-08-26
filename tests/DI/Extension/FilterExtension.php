<?php namespace Wasp\Test\DI\Extension;

use Wasp\DI\Extension;

/**
 * Filter extension
 *
 * @package Wasp
 * @subpackage Tests\Extensions
 * @author Dan Cox
 */
class FilterExtension extends Extension
{
	/**
	 * Alias
	 *
	 * @var string
	 */
	protected $alias = 'filter';

	/**
	 * The directory of the yml service file
	 *
	 * @var string
	 */
	protected $directory;

	/**
	 * Set up extension
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->directory = __DIR__;
	}

	/**
	 * Main extension method
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extension()
	{
		$this->loader->load('filter.yml');
	}

} // END class FilterExtension extends Extension
