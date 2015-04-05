<?php namespace Wasp\Test\Filters\FilterExtension;

use Wasp\DI\Extension;

/**
 * Extension class for filter tests
 *
 * @package Wasp
 * @subpackage Test\Filters
 * @author Dan Cox
 */
class FilterExtension extends Extension
{

	/**
	 * the alias
	 *
	 * @var string
	 */
	protected $alias = 'filters';

	/**
	 * filter directory
	 *
	 * @var string
	 */
	protected $directory;	


	/**
	 * set up filter details
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->directory = __DIR__;
	}

	/**
	 * The main extension method
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extension()
	{
		$this->loader->load('filters.yml');
	}

		
} // END class FilterExtensions extends Extension;

