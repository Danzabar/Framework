<?php

namespace Wasp\Environment;

use Wasp\Application\Application;
use Wasp\DI\DI;
use Wasp\Utils\Collection;
use Wasp\DI\DICompilerPassRegister;
use Wasp\Application\Profile;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The abstract environment class helps with app start and management.
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
abstract class AbstractEnvironment
{
    /**
     * A collection of configuration settings
     *
     * @var Collection
     */
    protected $config;

    /**
     * Instance of the App
     *
     * @var Application
     */
    protected $app;

    /**
     * An instance of the profile
     *
     * @var Profile
     */
    protected $profile;

    /**
     * Instance of the file system
     *
     * @var Filesystem
     */
    protected $fs;

    /**
     * Dependency Injector instance
     *
     * @var DI
     */
    protected $DI;

    /**
     * An array of settings from the profile class
     *
     * @var Array
     */
    public $settings;

    /**
     * Set up class dependencies
     */
    public function __construct()
    {
        $this->profile = new Profile(new Filesystem);
        $this->config = new Collection;
    }

    /**
     * Sets the configuration settings
     *
     * @return void
     */
    abstract public function config();

    /**
     * Builds the environment
     *
     * @return void
     */
    abstract public function build();

    /**
     * Loads the environment
     *
     * @return void
     */
    public function load()
    {
        $this->config();

        $this->syncProfileSettings();
        $this->createDependencyInjector();
        $this->createApplication();
        $this->injectDependencies();

        $this->build();
    }

    /**
     * Syncs profile settings
     *
     * @return void
     */
    public function syncProfileSettings()
    {
        if ($this->config->exists('profiles')) {
            $this->profile->addProfiles($this->config->get('profiles'));
        }

        if ($this->config->exists('config_files')) {
            $this->profile->addFiles($this->config->get('config_files'));
        }

        $this->profile->settings();
        $this->settings = $this->profile->getSettings()->all();
    }

    /**
     * Creates the Application instance
     *
     * @return void
     */
    public function createApplication()
    {
        $this->app = new Application($this->profile);
        $this->app->setDI($this->DI);
    }

    /**
     * Connects to specific database connection
     *
     * @param String $connection - the name of the connection
     * @return void
     */
    public function connectTo($connection)
    {
        // Not bothering to catch this, as I think an exception
        // Is better than returning a response like it was previously
        // doing.
        $this->DI->get('connection')->connect($connection);
    }

    /**
     * Connects to the default database connection
     *
     * @return void
     */
    public function connect()
    {
        $this->connectTo($this->getSetting('application', 'default_connection'));
    }

    /**
     * Injects the Application, Profile and Environment to the DI
     *
     * @return void
     */
    public function injectDependencies()
    {
        $this->DI->set('application', $this->app);
        $this->DI->set('profile', $this->profile);
        $this->DI->set('env', $this);
    }

    /**
     * Sets up twig templating engine
     *
     * @return void
     */
    public function setupTemplates($directory)
    {
        $twig = $this->DI->get('twigengine');
        $settings = $this->getSetting('templates', 'twig', array());

        $this->DI->get('template')->setDirectory($directory);

        $twig->create($settings);

        $this->DI->get('template')->addEngine($twig)->start();
    }

    /**
     * Creates an instance of the dependency injector
     *
     * @return void
     */
    public function createDependencyInjector()
    {
        $cacheDir = $this->getSetting('application', 'di_cache_directory');
        $cacheNS = $this->getSetting('application', 'di_cache_namespace');

        $this->DI = new DI(dirname(__DIR__) . '/Config/', $cacheDir, $cacheNS);

        $passes = DICompilerPassRegister::getPasses();

        foreach ($passes as $pass) {
            $this->DI->addCompilerPass(new $pass);
        }
    }

    /**
     * Returns settings from the profile
     *
     * @return void
     */
    public function getSetting($node, $key, $default = null)
    {
        if (isset($this->settings[$node][$key])) {
            return $this->settings[$node][$key];
        }

        return $default;
    }

    /**
     * Sets up database connection details
     *
     * @return void
     */
    public function setupConnections()
    {
        $this->DI->get('connections')->addBulk(
            $this->getSetting('database', 'connections')
        );
    }

    /**
     * Builds and compiles the DI
     *
     * @param String $serviceFile
     * @return void
     */
    public function buildDI($serviceFile = 'core')
    {
        $this->DI
            ->build()
            ->load($serviceFile)
            ->compile();
    }

    /**
     * Builds the cached version of the DI
     *
     * @param String $serviceFile
     * @return void
     */
    public function buildCacheDI($serviceFile = 'core')
    {
        $this->DI->buildContainerFromCache($serviceFile);
    }

    /**
     * Returns the application instance
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Returns the DI instance
     *
     * @return DI
     */
    public function getDI()
    {
        return $this->DI;
    }

} // END abstract class AbstractEnvironment
