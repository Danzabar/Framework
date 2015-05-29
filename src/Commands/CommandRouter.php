<?php namespace Wasp\Commands;

/**
 * Command Router, routes command actions based on input
 *
 * @package Wasp
 * @subpackage Wasp\Commands
 * @author Dan Cox
 */
class CommandRouter
{
	/**
	 * The command Object
	 *
	 * @var Object
	 */
	protected $object;

	/**
	 * Input from the object
	 *
	 * @var Object
	 */
	protected $input;

	/**
	 * An Array of option => functions
	 *
	 * @var Array
	 */
	protected $routes = [];

	/**
	 * Load the command object
	 *
	 * @param Object $object
	 * @return CommandRouter
	 * @author Dan Cox
	 */
	public function loadObject($object)
	{
		$this->object = $object;
		return $this;
	}

	/**
	 * Triggers the function related to input options
	 *
	 * @param \Symfony\Component\Console\Input\InputInterface
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function route($input)
	{
		foreach ($this->routes as $option => $function)
		{
			if ($input->getOption($option))
			{
				return call_user_func([$this->object, $function]);
			}
		}
	}

	/**
	 * Adds an array of routes
	 *
	 * @param Array $routes
	 * @return CommandRouter
	 * @author Dan Cox
	 */
	public function addRoutes(Array $routes)
	{
		$this->routes = array_merge($this->routes, $routes);

		return $this;
	}

	/**
	 * Adds a single route
	 *
	 * @param String $option
	 * @param String $function
	 * @return CommandRouter
	 * @author Dan Cox
	 */
	public function addRoute($option, $function)
	{
		$this->routes[$option] = $function;

		return $this;	
	}

	/**
	 * Returns the Routes array
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function getRoutes()
	{
		return $this->routes;
	}

} // END class CommandRouter
