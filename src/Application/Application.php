<?php namespace Wasp\Application;

use Wasp\Environment\Environment,
	Wasp\Exceptions\Application\UnknownEnvironment,
	Symfony\Component\HttpKernel\HttpKernel;

/**
 * Application class
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class Application
{
	/**
	 * Registered environments
	 *
	 * @var Array
	 */
	protected $environments = [];

	/**
	 * DI instance from the environment
	 *
	 * @var \Wasp\DI\DI
	 **/
	protected $DI;

	/**
	 * An environment instance
	 *
	 * @var Object
	 */
	public $env;

	/**
	 * A setup instance of the profile class
	 *
	 * @var \Wasp\Application\Profile
	 **/
	public $profile;	

	
	/**
	 * Set up Application Defaults
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($profile = NULL)
	{
		$this->profile = $profile;

		// Default Environments
		$this->registerEnvironment('test', 'Wasp\Environment\Test');
	}

	/**
	 * Start the http kernel events
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function react()
	{
		return $this->DI->get('kernel')->handle($this->DI->get('request')->getRequest());
	}

	/**
	 * Responds to the current request
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function respond()
	{
		$response = $this->react();

		$response->send();
	}

	/**
	 * Loads an environment by name
	 *
	 * @param String $name - The name of the environment
	 * @return void
	 * @author Dan Cox
	 */
	public function loadEnv($name)
	{
		// Get the Environment's class
		$class = $this->getEnvironment($name);
	
		// Create reflection and invoke a new instance
		$reflection = new \ReflectionClass($class);
		$instance = $reflection->newInstance();

		// Call the Load method;
		$this->env = $instance->load($this);
		$this->DI = $this->env->getDI();
	}

	/**
	 * Registers an environment so it can be used in App start up.
	 *
	 * @param String $name - the name of the environment
	 * @param String $class - fully qualified class name as a string
	 * @return Application
	 * @author Dan Cox
	 */
	public function registerEnvironment($name, $class)
	{
		$this->environments[$name] = $class;
		return $this;
	}

	/**
	 * Registers an array of environments
	 *
	 * @param Array $environments
	 * @return Application
	 * @author Dan Cox
	 */
	public function registerEnvironments(Array $environments)
	{
		foreach ($environments as $name => $class)
		{
			$this->registerEnvironment($name, $class);
		}

		return $this;
	}

	/**
	 * Gets an environments class name by its label
	 *
	 * @param String $name - the name of the environment
	 * @return String
	 * @author Dan Cox
	 */
	public function getEnvironment($name)
	{
		if (array_key_exists($name, $this->environments))
		{
			return $this->environments[$name];
		}

		// Not found
		throw new UnknownEnvironment($name);
	}

	/**
	 * Removes the registered environment from the Application.
	 *
	 * @param String $name - the environment name
	 * @return Application
	 * @author Dan Cox
	 */
	public function deregisterEnvironment($name)
	{
		if (array_key_exists($name, $this->environments))
		{
			unset($this->environments[$name]);
			return $this;
		}

		// Throw exception
		throw new UnknownEnvironment($name);
	}

	/**
	 * Returns the DI instance
	 *
	 * @return \Wasp\DI\DI
	 * @author Dan Cox
	 */
	public function getDI()
	{
		return $this->DI;
	}

} // END class Application
