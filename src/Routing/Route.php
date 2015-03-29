<?php namespace Wasp\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;

/**
 * Adds routes and route groups to the collection
 *
 * @package Wasp
 * @subpackage Routing
 * @author Dan Cox
 **/
class Route
{

	/**
	 * Route collection instance
	 *
	 * @var \Symfony\Component\Routing\RouteCollection
	 **/
	protected $collection;

	/**
	 * Instance of Symfony Collection
	 *
	 * @var Object
	 **/
	protected $activeGroup;

	/**
	 * Load dependencies
	 *
	 * @param \Symfony\Component\Routing\RouteCollection $routeCollection
 	 * @author Dan Cox
	 **/
	public function __construct($routeCollection)
	{
		$this->collection = $routeCollection;
	}

	/**
	 * Creates a simple route
	 *
	 * @param String $name - the route name
	 * @param String $uri
	 * @param Array $methods
	 * @param Array $defaults
	 * @param Array $requirements
	 * @param String $host
	 * @param Array $schemes	
	 * @return void
	 * @author Dan Cox
	 **/
	public function add(
		$name, 
		$uri, 
		$methods = Array(),
		$defaults = Array(), 
		$requirements = Array(), 
		$host = '', 
		$schemes = Array()		
	)
	{
		$route = new SymfonyRoute(
			$uri, 
			$defaults,
			$requirements,
			Array(),
			$host,
			$schemes,
			$methods
		);

		$this->processRoute($name, $route);	
	}	

	/**
	 * Adds a route to the appropriate collection
	 *
	 * @param String $name
	 * @param SymfonyRoute $route
	 * @return void
	 * @author Dan Cox
	 **/
	public function processRoute($name, $route)
	{
		if (!is_null($this->activeGroup)) 
		{
			$this->activeGroup->add($name, $route);

		} else 
		{
			$this->collection->add($name, $route);
		}		
	}

	/**
	 * Adds a route group
	 *
	 * @param Array $defaults
	 * @param Closure $callback
	 * @return void
	 * @author Dan Cox
	 **/
	public function group(Array $defaults, $callback)
	{
		$this->activeGroup = new \Symfony\Component\Routing\RouteCollection;
		$this->activeGroup->addDefaults($defaults);

		call_user_func_array($callback, [$this]);

		$this->addGroup();
	}

	/**
	 * Adds prefix to the current active group
	 *
	 * @param String $prefix
	 * @return Route
	 * @author Dan Cox
	 **/
	public function addPrefix($prefix)
	{
		$this->activeGroup->addPrefix($prefix);			
		return $this;
	}

	/**
	 * Adds the current active group
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function addGroup()
	{
		$this->collection->addCollection($this->activeGroup);

		$this->activeGroup = NULL;
	}

} // END class Route

