<?php namespace Wasp\Routing;

use Wasp\DI\DependencyInjectionAwareTrait,
	Symfony\Component\Routing\Generator\UrlGenerator,
	Symfony\Component\Routing\RequestContext;

/**
 * Url class for generating urls from routes
 *
 * @package Wasp
 * @subpackage Routing
 * @author Dan Cox
 **/
class URL
{
	use DependencyInjectionAwareTrait;

	/**
	 * Instance of the Url Generator
	 *
	 * @var \Symfony\Component\Routing\Generator\UrlGenerator
	 **/
	protected $generator;

	/**
	 * Instance of the Request Context
	 *
	 * @var \Symfony\Component\Routing\RequestContext
	 **/
	protected $context;

	/**
	 * Instance of the request class
	 *
	 * @var \Wasp\Routing\Request
	 **/
	protected $request;

	/**
	 * Instance of the route collection class
	 *
	 * @var \Symfony\Component\Routing\RouteCollection
	 **/
	protected $collection;

	/**
	 * Load dependencies
	 *
	 * @param \Wasp\Routing\Request $request
	 * @param \Symfony\Component\Routing\RouteCollection $collection
	 * @author Dan Cox
	 **/
	public function __construct($request, $collection)
	{
		$this->request = $request;
		$this->collection = $collection;
	}

	/**
	 * Returns a instance of the Request Context class
	 *
	 * @return \Symfony\Component\Routing\RequestContext
	 * @author Dan Cox
	 **/
	public function context()
	{
		return $this->context = new RequestContext();
	}

	/**
	 * Creates the URL Generator object
	 *
	 * @return \Symfony\Component\Routing\Generator\UrlGenerator
	 * @author Dan Cox
	 **/
	public function generator()
	{
		$request = $this->request->fromGlobals();

		return $this->generator = new UrlGenerator(
			   $this->collection,
			   $this->context()->fromRequest($request)
		);
	}

	/**
	 * Generate a url based on a route name
	 *
	 * @param String $name - the route name
	 * @param Array $params
	 * @return String
	 * @author Dan Cox
	 **/
	public function route($name, $params = Array())
	{
		$url = $this->generator()->generate($name, $params);

		return $url;
	}

} // END class URL
