<?php namespace Wasp\DI;

/**
 * Decorator class for Service Mockeries
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class ServiceMockeryDecorator
{
	/**
	 * The Mockery
	 *
	 * @var Object
	 */
	protected $mockery;

	/**
	 * Creates the Mockery based on the service name
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($service)
	{
		$this->mockery = \Mockery::mock($service);

		// Add a call for the DI
		$this->mockery->shouldReceive('getDI')->andReturn($this->mockery);
		$this->mockery->shouldReceive('setDI')->andReturn($this->mockery);
	}

	/**
	 * Proxy for calling
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __call($method, $args = Array())
	{
		return call_user_func_array([$this->mockery, $method], $args);
	}

	/**
	 * Proxy for properties
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __get($key)
	{
		return $this->mockery->$key;
	}

	/**
	 * Proxy for settings
	 *
	 * @return ServiceMockeryDecorator
	 * @author Dan Cox
	 */
	public function __set($key, $value)
	{
		$this->mockery->$key = $value;
		return $this;
	}

} // END class ServiceMockeryDecorator
