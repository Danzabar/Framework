<?php

use Wasp\DI\DependencyInjectionAwareTrait;


/**
 * Just a test service
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class Service
{
	use DependencyInjectionAwareTrait;
	
	/**
	 * An Instance of the Test Library
	 *
	 * @var Object
	 */
	protected $library;

	/**
	 * Set up class dependencies
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($library = NULL)
	{
		$this->library = $library;
	}

	/**
	 * Returns the Library
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function getLibrary()
	{
		return $this->library;
	}
	
} // END class Service
