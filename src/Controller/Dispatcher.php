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
	 * @return void
	 * @author Dan Cox
	 */
	public function dispatch($action)
	{
		$this->extract($action);
		
		// Create the Reflection
		$this->reflection = new \ReflectionClass($this->controller);		

		// Fire the method
		$this->response = $this->fire();

		// Analyse the response
		$this->formatResponse();
	}

	/**
	 * Formats the response
	 *
	 * @return Void
	 * @author Dan Cox
	 */
	public function formatResponse()
	{
		// If the response is not a response object, create it. 
		if(!is_object($this->response))
		{
			// Create the response
			$this->response = $this->DI->get('response')->make($this->response, 200);
		} 

		// Send it
		$this->response->send();
	}

	/**
	 * Fires method using the reflection instance
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function fire()
	{
		$instance = $this->reflection->newInstanceArgs(['DI' => $this->DI]);
		$method = $this->reflection->getMethod($this->method);

		return $method->invokeArgs($instance, Array());
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
