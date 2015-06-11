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
	 * Route prefix
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * An array of rest methods and values
	 *
	 * @var Array
	 */
	private $typeMap = [
		'all'			=> ['uri' => '', 'method' => ['GET']],
		'show'			=> ['uri' => '/{id}', 'method' => ['GET']],
		'edit'			=> ['uri' => '/edit/{id}', 'method' => ['GET']],
		'create'		=> ['uri' => '/new', 'method' => ['POST']],
		'new'			=> ['uri' => '/new', 'method' => ['GET']],
		'update'		=> ['uri' => '/update/{id}', 'method' => ['PATCH']],
		'delete'		=> ['uri' => '/delete/{id}', 'method' => ['DELETE']]
	];

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
			(is_null($this->prefix) ? $uri : $this->prefix . $uri), 
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
	 * Uses the standard Rest controller to provide a rest 
	 * structure for an entity
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $entity - The qualified entity class name
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function resource($name, $uri, $entity)
	{
		/**
		 *	We can use the "rest" method to create the routes
		 *	And provide a default variable for the entity
		 */
		$this->rest($name, $uri, 'Wasp\Controller\RestController', ['all', 'show', 'update', 'create', 'delete'], ['entity' => $entity]);
	}

	/**
	 * Creates a set of RESTful routes
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $action
	 * @param Array $methods
	 * @param Array $defaults
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function rest($name, $uri, $action, $methods = Array(), $defaults = Array())
	{
		$methods = (!empty($methods) ? $methods : Array('all', 'show', 'update', 'create', 'new', 'edit', 'delete'));
		
		foreach ($methods as $method)
		{
			$m = $this->typeMap[$method];

			$this->addRestRoute(
				$name .'.'. $method,
				$uri . $m['uri'],
				$action .'::'. $method,
				$m['method'],
				$defaults
			);
		}
	}

	/**
	 * Adds a rest route to the collection
	 *
	 * @param String $name
	 * @param String $uri
	 * @param String $method
	 * @param String $action
	 * @param Array $defaults
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function addRestRoute($name, $uri, $action, $method,  $defaults = Array())
	{
		$this->add($name, $uri, $method, array_merge(['_controller' => $action], $defaults));
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
		$this->prefix = null;
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
		$this->prefix = $prefix;			
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

