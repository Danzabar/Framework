<?php namespace Wasp\Application;

use Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\Config\FileLocator,
	Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Dependency Injector
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class DI
{
	/**
	 * Directory in which we find the services YAML files
	 *
	 * @var string
	 */
	protected $directory;

	/**
	 * Instance of the Symfony Container
	 *
	 * @var Object
	 */
	protected static $container;

	/**
	 * An array of mocks for depedencies
	 *
	 * @var Array
	 */
	protected static $mocks;

	/**
	 * An array of mock parameters similar to above
	 *
	 * @var Array
	 */
	protected static $params;

	/**
	 * Set up class settings
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($directory = NULL)
	{
		$this->directory = $directory;
	}

	/**
	 * Clears all the mocks we may have set
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function clearMocks()
	{
		static::$mocks = Array();
		static::$params = Array();
		
		return $this;
	}

	/**
	 * Adds a mocked item into the container
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function addMock($key, $object)
	{
		static::$mocks[$key] = $object;
		
		return $this;
	}

	/**
	 * Removes a single mock by key
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function removeMock($key)
	{
		if(array_key_exists($key, static::$mocks))
		{
			unset(static::$mocks[$key]);
		}

		return $this;
	}

	/**
	 * Adds a mocked parameter
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function addParam($key, $value)
	{
		static::$params[$key] = $value;

		return $this;
	}

	/**
	 * Removes a param by key
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function removeParam($key)
	{
		if(array_key_exists($key, static::$params))
		{
			unset(static::$params[$key]);
		}

		return $this;
	}

	/**
	 * Returns all mocks
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function getMocks()
	{
		return static::$mocks;
	}

	/**
	 * Returns all params
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function getParams()
	{
		return static::$params;
	}

	/**
	 * Gets the value of directory
	 *
	 * @return String
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * Sets the value of directory
	 *
	 * @param String $directory The directory in which we find the services YAML files
	 *
	 * @return DI
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;
		return $this;
	}


} // END class DI
