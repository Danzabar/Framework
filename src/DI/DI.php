<?php

namespace Wasp\DI;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Wasp\Exceptions;
use Wasp\DI\ExtensionRegister;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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
     * Instance of the extension register
     *
     * @var Wasp\DI\ExtensionRegister
     */
    protected $extensions;

    /**
     * Location of the AppCache
     * eg. /var/www/wasp/cache/AppCache.php
     *
     * @var string
     */
    protected $cache_directory;

    /**
     * Fully qualified class name for cache.
     * eg Wasp\Cache
     *
     * @var string
     */
    protected $cache_namespace;

    /**
     * Class name of the Cache
     *
     * @var string
     **/
    protected $cache_class = 'AppCache';

    /**
     * Set up class settings
     *
     * @param String $directory
     * @return void
     */
    public function __construct($directory = null, $cache_directory = null, $cache_namespace = null)
    {
        $default_dir = dirname(__DIR__) . '/Application/Cache/AppCache.php';

        $this->directory = $directory;
        $this->extensions = new ExtensionRegister;
        $this->cache_directory = (!is_null($cache_directory) ? $cache_directory : $default_dir);
        $this->cache_namespace = (!is_null($cache_namespace) ? $cache_namespace : 'Wasp\Application\Cache');

        $this->cache = new ConfigCache($this->cache_directory, false);
        static::$container = new ContainerBuilder;
    }

    /**
     * Builds the DI container
     *
     * @return DI
     */
    public function build()
    {
        // We must have a directory set;
        if (is_null($this->directory)) {
            throw new Exceptions\DI\InvalidServiceDirectory($this);
        }

        // Load extensions, if there are any
        $this->loadExtensions();

        // This just builds the components, ready to load a service definition file
        static::$loader = new YamlFileLoader(static::$container, new FileLocator($this->directory));
        return $this;
    }

    /**
     * Loads registered extensions if there are any
     *
     * @return void
     */
    public function loadExtensions()
    {
        $this->extensions->register(static::$container);
    }

    /**
     * Compiles the container
     *
     * @return DI
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
     */
    public function buildContainerFromCache($serviceFile)
    {
        if (!$this->cache->isFresh()) {
            $this
                ->build()
                ->load($serviceFile)
                ->compile()
                ->cache();
        }

        $container = new \ReflectionClass($this->cache_namespace . '\\' . $this->cache_class);

        static::$container = $container->newInstance();
    }

    /**
     * Caches the compiled container
     *
     * @return DI
     */
    public function cache()
    {
        $dump = new PhpDumper(static::$container);

        $this->cache->write(
            $dump->dump(['class' => $this->cache_class, 'namespace' => $this->cache_namespace]),
            static::$container->getResources()
        );

        return $this;
    }

    /**
     * Register a compiler pass class
     *
     * @param Object $pass
     * @return DI
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
     */
    public function get($service)
    {
        return static::$container->get($service);
    }

    /**
     * Sets a definition on the un-compiled container
     *
     * @param String $id
     * @param Object $service
     * @param String $scope
     * @return void
     */
    public function set($id, $service, $scope = ContainerBuilder::SCOPE_CONTAINER)
    {
        static::$container->set($id, $service, $scope);
    }

    /**
     * Get param by name
     *
     * @param String $key
     * @return Mixed
     */
    public function param($key)
    {
        return static::$container->getParameter($key);
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
     */
    public static function getContainer()
    {
        return static::$container;
    }

    /**
     * Returns the extension instance
     *
     * @return Wasp\DI\ExtensionRegister
     */
    public function extensions()
    {
        return $this->extensions;
    }
} // END class DI
