<?php namespace Wasp\Application;

use Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\Config\FileLocator,
	Wasp\Exceptions,
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
	 * Loader instance
	 *
	 * @var Object
	 */
	protected static $loader;

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
	 * Builds the DI container
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function build()
	{
		// We must have a directory set;
		if(is_null($this->directory))
		{
			throw new Exceptions\DI\InvalidServiceDirectory($this);
		}

		// This just builds the components, ready to load a service definition file
		static::$container = new ContainerBuilder;
		static::$loader = new YamlFileLoader(static::$container, new FileLocator($this->directory));

		return $this;
	}

	/**
	 * Loads a file by name
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function load($filename)
	{
		static::$loader->load($filename.'.yml');
		return $this;
	}

	/**
	 * Returns a service
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function get($service)
	{
		// If we have a mock version, serve that up instead.
		if(array_key_exists($service, static::$mocks))
		{
			return static::$mocks[$service];
		}

		return static::$container->get($service);
	}

	/**
	 * Get param by name
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function param($key)
	{
		// Again if we have a mocked version, use that
		if(array_key_exists($key, static::$params))
		{
			return static::$params[$key];
		}

		return static::$container->getParameter($key);
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
