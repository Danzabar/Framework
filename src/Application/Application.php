<?php namespace Wasp\Application;

use Wasp\Environment\Environment,
	Wasp\Exceptions\Application\UnknownEnvironment;

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
	 * An environment instance
	 *
	 * @var Object
	 */
	public $env;

	/**
	 * Set up Application Defaults
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct()
	{
		// Default Environments
		$this->registerEnvironment('test', 'Wasp\Environment\Test');
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

} // END class Application
