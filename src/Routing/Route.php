<?php namespace Wasp\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute,
	Wasp\Utils\TypeMapTrait;

/**
 * Adds routes and route groups to the collection
 *
 * @package Wasp
 * @subpackage Routing
 * @author Dan Cox
 **/
class Route
{
	use TypeMapTrait;

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
	 * Creates a set of RESTful routes
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 * @return void
	 * @author Dan Cox
	 */
	public function rest($name, $uri, $action, $methods = Array())
	{
		$methods = (!empty($methods) ? $methods : Array('show', 'update', 'create', 'new', 'edit', 'delete'));
		
		$this->typeMap = [
			'show'			=> 'showRest',
			'edit'			=> 'editRest',
			'create'		=> 'createRest',
			'new'			=> 'newRest',
			'update'		=> 'updateRest',
			'delete'		=> 'deleteRest'
		];

		foreach ($methods as $method)
		{
			$this->map($method, '\Wasp\Exceptions\Routing\InvalidRestOption', [$name, $uri, $action]);
		}
	}

	/**
	 * Creates a "delete" rest route
	 * 
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function deleteRest($name, $uri, $action)
	{
		$this->add(
			$name . '.delete',
			$uri . '/delete/{id}',
			Array('DELETE'),
			Array('controller' => $action .'::delete')	
		);
	}

	/**
	 * Creates an "update" rest route
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function updateRest($name, $uri, $action)
	{
		$this->add(
			$name . '.update',
			$uri . '/update/{id}',
			Array('PATCH'),
			Array('controller'  => $action .'::update')	
		);
	}

	/**
	 * Creates a "new" rest route
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function newRest($name, $uri, $action)
	{
		$this->add(
			$name . '.new',
			$uri . '/new',
			Array('GET'),
			Array('controller' => $action . '::new')
		);
	}

	/**
	 * Creates a "create" rest option
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function createRest($name, $uri, $action)
	{
		$this->add(
			$name . '.create',
			$uri . '/new',
			Array('POST'),
			Array('controller' => $action . '::create')
		);
	}	

	/**
	 * Adds an "edit" rest route
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function editRest($name, $uri, $action)
	{
		$this->add(
			$name .'.edit',
			$uri . '/edit/{id}',
			Array('GET'),
			Array('controller' => $action . '::edit')
		);
	}

	/**
	 * creates a "show" rest route
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function showRest($name, $uri, $action)
	{
		$this->add(
			$name . '.show', 
			$uri . '/{id}',
			Array('GET'),
			Array('controller' => $action .'::show')
		);
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

