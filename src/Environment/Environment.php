<?php namespace Wasp\Environment;

use Wasp\Application\Application,
	Wasp\DI\DICompilerPassRegister,
	Wasp\DI\DI;


/**
 * Base environment class, Determines actions taken on Application Launch.
 *
 * @package Wasp
 * @subpackage Environment
 * @author Dan Cox
 */
class Environment
{
	/**
	 * The App instance
	 *
	 * @var Object
	 */
	protected $App;

	/**
	 * Settings as defined by the profile
	 *
	 * @var Array
	 */
	protected $settings;

	/**
	 * The DI instance
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Sets the App and DI instances and Calls the Child Class function if exists
	 *
	 * @return Environment
	 * @author Dan Cox
	 */
	public function load(Application $App)
	{
		$this->App = $App;

		$this->settings = (!is_null($this->App->profile) ? $this->App->profile->getSettings() : Array());

		$this->injectDependencies();

		// If the Child Environment Class has a Setup function, call it.
		if (method_exists($this, 'setup'))
		{
			$this->setup();
		}

		return $this;
	}

	/**
	 * Creates a DI instance using the settings from the Application Profile
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function DIInstance()
	{
		$di_cache_dir = (isset($this->settings['application']['di_cache_directory']) ? $this->settings['application']['di_cache_directory'] : NULL);
		$di_cache_ns = (isset($this->settings['application']['di_cache_namespace']) ? $this->settings['application']['di_cache_namespace'] : NULL);			

		$this->DI = new DI(dirname(__DIR__) . '/Config/', $di_cache_dir, $di_cache_ns);

		$this->loadRegisterCompilerPasses();
	}

	/**
	 * inject the missing Application and Profile class definitions
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function injectDependencies()
	{
		$this->DIInstance();

		$this->DI->set('application', $this->App);
		$this->DI->set('profile', $this->App->profile);
	}

	/**
	 * Loads registered compiler passes
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function loadRegisterCompilerPasses()
	{
		$passes = DICompilerPassRegister::getPasses();

		foreach ($passes as $pass)
		{
			$reflection = new \ReflectionClass($pass);

			$this->DI->addCompilerPass($reflection->newInstance());
		}
	}

	/**
	 * Creates the templating engines and adds them to the delegator
	 *
	 * @param String $directory
	 * @return void
	 * @author Dan Cox
	 */
	public function startTemplating($directory)
	{
		$twig = $this->DI->get('twigengine');
		$twigConfig = (isset($this->settings['templates']['twig']) ? $this->settings['templates']['twig'] : Array());

		$this->DI->get('template')
				 ->setDirectory($directory);
		
		$twig->create($twigConfig);

		$this->DI->get('template')
				 ->addEngine($twig)
				 ->start();
	}

	/**
	 * Creates connection to named database connection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function connectTo($connection)
	{
		try {
			
			$this->DI->get('connection')->connect($connection);

		} catch (\Exception $e) {

			$response = $this->DI->get('response')->make('<p>The database connection details seem invalid, please check them.</p>', 200);
			$response->send();		
		}
	}

	/**
	 * Uses profile settings to connect to the default database
	 *
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function connect()
	{
		if ($this->settings['application']['default_connection'] !== '')
		{
			$this->connectTo($this->settings['application']['default_connection']);

			return true;
		}		

		return false;
	}

	/**
	 * Adds connection details
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setupConnections()
	{
		$this->DI->get('connections')->addBulk($this->settings['database']['connections']);
	}

	/**
	 * Loads the DI with a specific Service File
	 * Important to note that this function does not use the CACHED DI Container.
	 *
	 * @param String $serviceFile - the name of the service YAML file
	 * @return void
	 * @author Dan Cox
	 */
	public function createDI($serviceFile = 'core')
	{
		$this->DI->build()->load($serviceFile)->compile();
	}

	/**
	 * Creates a DI instance from Cache;
	 *
	 * @param String $serviceFile - the name of the service YAML file
	 * @return void
	 * @author Dan Cox
	 */
	public function createDIFromCache($serviceFile = 'core')
	{
		$this->DI->buildContainerFromCache($serviceFile);
	}

	/**
	 * Returns the DI Instance
	 *
	 * @return Object|NULL
	 * @author Dan Cox
	 */
	public function getDI()
	{
		return $this->DI;
	}


} // END class Environment
