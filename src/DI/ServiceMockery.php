<?php namespace Wasp\DI;

use Wasp\DI\ServiceMockeryLibrary,
	\Mockery as m;

/**
 * Service mockery class is used to create mockery instances of the DI services
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class ServiceMockery
{
	/**
	 * Mockery instance of the Service
	 *
	 * @var Object	
	 */
	protected $mockery;

	/**
	 * The name of the service
	 *
	 * @var string
	 */
	protected $serviceName;

	/**
	 * Instance of the Service Mockery Library
	 *
	 * @var Object
	 */
	protected $library;

	/**
	 * Set up the Mockery instance
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($name)
	{
		$this->serviceName = $name;

		// Create a new mockery instance of this.
		$this->mockery = m::mock($name);	
		$this->library = new ServiceMockeryLibrary;
	}

	/**
	 * Returns the Mockery object
	 *
	 * @return Mockery
	 * @author Dan Cox
	 */
	public function getMock()
	{
		return $this->mockery;
	}

	/**
	 * Adds the Mockery instance to the library
	 *
	 * @return ServiceMockery
	 * @author Dan Cox
	 */
	public function add()
	{
		$this->library->add($this->serviceName, $this->mockery);						
		return $this;
	}

	/**
	 * Remove from the library
	 *
	 * @return ServiceMockery
	 * @author Dan Cox
	 */
	public function remove()
	{
		$this->library->remove($this->serviceName);
		return $this;
	}

} // END class ServiceMockery
