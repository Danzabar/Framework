<?php namespace Wasp\Controller;

use Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Controller dispatcher
 *
 * @package Wasp
 * @subpackage Controller
 * @author Dan Cox
 */
class Dispatcher
{
	use DependencyInjectionAwareTrait;
	
	/**
	 * The action from the route
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Qualified controller string
	 *
	 * @var string
	 */
	protected $controller;

	/**
	 * The method to call on the controller
	 *
	 * @var string
	 */
	protected $method;

	/**
	 * Controller reflection
	 *
	 * @var Object
	 */
	protected $reflection;

	/**
	 * Response from the controller
	 *
	 * @var Mixed
	 */
	protected $response;

	/**
	 * Creates a new reflection from the given action
	 *
	 * @param String $action
	 * @param Array $params
	 * @param Array $filters
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @author Dan Cox
	 */
	public function dispatch($action, Array $params = Array(), Array $filters = Array())
	{
		$this->extract($action);
		
		// Create the Reflection
		$this->reflection = new \ReflectionClass($this->controller);		

		// Before dispatch filters
		$this->before($filters);

		// Fire the method
		$this->response = $this->fire($params);

		// Analyse the response
		return $this->formatResponse();
	}

	/**
	 * Triggers a single filter
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function triggerFilter($filter, $method)
	{
		$filters = $this->DI->get('filter')->prepare();
		$filters->fire($filter, $method);
	}

	/**
	 * Checks and fires before filters
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function before(Array $filters)
	{
		if (array_key_exists('before', $filters)) {
			$this->triggerFilter($filters['before']['filter'], $filters['before']['method']);		
		}
	}

	/**
	 * Formats the response
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @author Dan Cox
	 */
	public function formatResponse()
	{
		// If the response is not a response object, create it. 
		if (!is_object($this->response))
		{
			// Create the response
			$this->response = $this->DI->get('response')->make($this->response, 200);
		} 

		return $this->response;
	}

	/**
	 * Fires method using the reflection instance
	 *
	 * @param Array $params
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function fire(Array $params)
	{
		$instance = $this->reflection->newInstanceArgs(['DI' => $this->DI]);
		$method = $this->reflection->getMethod($this->method);

		return $method->invokeArgs($instance, $params);
	}

	/**
	 * Extracts the controller and method from the action
	 *
	 * @param String $action
	 * @author Dan Cox
	 */
	public function extract($action)
	{
		$parts = explode('::', $action);

		$this->controller = $parts[0];
		$this->method = $parts[1];
	}


} // END class Dispatcher
