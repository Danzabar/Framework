<?php namespace Wasp\DI;

use Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\Config\FileLocator,
	Wasp\Exceptions,
	Symfony\Component\DependencyInjection\Dumper\PhpDumper,
	Symfony\Component\Config\ConfigCache,
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
	 * Config Cache Instance
	 *
	 * @var Object
	 */
	protected $cache;

	/**
	 * Set up class settings
	 *
	 * @param String $directory
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($directory = NULL)
	{
		$this->directory = $directory;
		$this->cache = new ConfigCache(dirname(__DIR__) . '/Application/Cache/AppCache.php', False);

		static::$container = new ContainerBuilder;
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
		if (is_null($this->directory))
		{
			throw new Exceptions\DI\InvalidServiceDirectory($this);
		}

		// This just builds the components, ready to load a service definition file
		static::$loader = new YamlFileLoader(static::$container, new FileLocator($this->directory));
		return $this;
	}

	/**
	 * Compiles the container
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function compile()
	{
		static::$container->compile();
		return $this;
	}

	/**
	 * Attempts to use cache or creates cache
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function buildContainerFromCache($serviceFile)
	{
		if (!$this->cache->isFresh())
		{
			$this
				->build()
				->load($serviceFile)
				->compile()
				->cache();

		}

		static::$container = new \Wasp\Application\Cache\AppCache;
	}

	/**
	 * Caches the compiled container
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function cache()
	{
		$dump = new PhpDumper(static::$container);
		
		$this->cache->write(
			$dump->dump(['class' => 'AppCache', 'namespace' => 'Wasp\Application\Cache']), 
			static::$container->getResources()
		);

		return $this;
	}

	/**
	 * Register a compiler pass class
	 *
	 * @param Object $pass
	 * @return DI
	 * @author Dan Cox
	 */
	public function addCompilerPass($pass)
	{
		static::$container->addCompilerPass($pass);

		return $this;
	}

	/**
	 * Loads a file by name
	 *
	 * @param String $filename
	 * @return DI
	 * @author Dan Cox
	 */
	public function load($filename)
	{
		static::$loader->load($filename . '.yml');
		return $this;
	}

	/**
	 * Returns a service
	 *
	 * @param String $service
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function get($service)
	{
		return static::$container->get($service);
	}

	/**
	 * Get param by name
	 *
	 * @param String $key
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function param($key)
	{
		return static::$container->getParameter($key);
	}

	/**
	 * Returns the Container
	 *
	 * @return Object
	 * @author Dan Cox
	 */
	public static function getContainer()
	{
		return static::$container;
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
	 * @return DI
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;
		return $this;
	}

	/**
	 * Returns the DI Container
	 *
	 * @return Object
	 * @author Dan Cox
	 */
	public static function getContainer()
	{
		return static::$container;
	}

} // END class DI
