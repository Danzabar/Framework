<?php namespace Wasp\DI;

/**
 * A storage for the ServiceMockery classes
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class ServiceMockeryLibrary
{

	/**
	 * An Array of Services
	 *
	 * @var Array
	 */
	protected static $services = Array();

	/**
	 * Adds an item to the library
	 *
	 * @param String $name -  The name of the service
	 * @return ServiceMockeryLibrary
	 * @author Dan Cox
	 */
	public function add($name)
	{
		static::$services[$name] = $name;	
		return $this;
	}

	/**
	 * Finds a service by Name
	 *
	 * @param String $name - The name of the service
	 * @return String|NULL
	 * @author Dan Cox
	 */
	public function find($name)
	{
		if(array_key_exists($name, static::$services)) 
		{
			return static::$services[$name];
		}

		return NULL;
	}

	/**
	 * Remove a service by Name
	 *
	 * @param String $name - The name of the service
	 * @return ServiceMockeryLibrary
	 * @author Dan Cox
	 */
	public function remove($name)
	{
		if(array_key_exists($name, static::$services))
		{
			unset(static::$services[$name]);	
		}

		return $this;
	}

	/**
	 * Returns All definitions
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function all()
	{
		return static::$services;
	}

	/**
	 * Removes all definitions
	 *
	 * @return ServiceMockeryLibrary
	 * @author Dan Cox
	 */
	public function clear()
	{
		static::$services = Array();

		return $this;
	}

} // END class ServiceMockeryLibrary
