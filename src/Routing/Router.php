<?php namespace Wasp\Routing;

use Wasp\DI\DependencyInjectionAwareTrait,
	Symfony\Component\Routing\RequestContext,
	Symfony\Component\Routing\Matcher\UrlMatcher;

/**
 * Router class matches requests to routes
 *
 * @package Wasp
 * @subpackage Routing
 * @author Dan Cox
 */
class Router
{
	use DependencyInjectionAwareTrait;	

	/**
	 * The request URI
	 *
	 * @var String
	 */
	protected $uri;

	/**
	 * Current route
	 *
	 * @var Array
	 */
	protected $match;

	/**
	 * An Array of parameters from the Current route
	 *
	 * @var Array
	 */
	protected $params;

	/**
	 * An Array of active filters for this route
	 *
	 * @var Array
	 */
	protected $filters;

	/**
	 * Request Context instance
	 *
	 * @var \Symfony\Component\Routing\RequestContext
	 */
	protected $context;

	/**
	 * Set up class env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->params = Array();
	}

	/**
	 * Returns an instance of the request context class
	 *
	 * @return \Symfony\Component\Routing\RequestContext
	 * @author Dan Cox
	 */
	public function context()
	{
		return $this->context = new RequestContext();
	}

	/**
	 * Resolve a uri to a route and Dispatches
	 *
	 * @param String $uri
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @author Dan Cox
	 */
	public function resolve($uri)
	{
		$this->uri = $uri;
		$this->match = $this->match();

		$this->extractParams();
		$this->extractFilters();

		$dispatcher = $this->DI->get('dispatcher');
		return $dispatcher->dispatch($this->match['controller'], $this->params, $this->filters);
	}

	/**
	 * Extracts the params for the dispatcher from the matched route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extractParams()
	{
		$standardKeys = ['controller', 'before', 'after', 'subdomain', '_route'];

		foreach ($this->match as $key => $potential)
		{
			if (!in_array($key, $standardKeys))
			{
				$this->params[$key] = $potential;
			}
		}
	}

	/**
	 * Extracts filter details from the matched route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extractFilters()
	{	
		$this->filters = [];
		$filterKeys = ['before', 'after'];

		foreach ($filterKeys as $fk)
		{
			if (array_key_exists($fk, $this->match)) {
				$this->filters[$fk] = Array('filter' => $this->match[$fk][0], 'method' => $this->match[$fk][1]);
			}
		}
	}

	/**
	 * Matches a request to a specified route
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function match()
	{
		$request = $this->DI->get('request')->getRequest();

		$match = new UrlMatcher(
			$this->DI->get('route_collection'),
			$this->context()->fromRequest($request)
		);		

		return $match->match($this->uri);
	}

	/**
	 * Returns the Matched route
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function currentRoute()
	{
		return $this->match;
	}

} // END class Router
