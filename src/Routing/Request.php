<?php namespace Wasp\Routing;

use Symfony\Component\HttpFoundation\Request as SymRequest;

/**
 * Requests
 *
 * @package Wasp
 * @subpackage Routing
 * @author Dan Cox
 */
class Request
{
	/**
	 * Symfony Request Class Instance
	 *
	 * @var Object
	 */
	protected $request;

	/**
	 * Get the request object from the current global var
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function fromGlobals()
	{
		$this->request = SymRequest::createFromGlobals();
	}

	/**
	 * Creates a request from given details 
	 *
	 * @param String $uri
	 * @param String $type
	 * @param Array $params
	 * @return void
	 * @author Dan Cox
	 */
	public function make($uri, $type = 'GET', $params = Array())
	{
		$this->request = SymRequest::create($uri, $type, $params);
	}

	/**
	 * Magic Getter for the request object
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function __get($key)
	{
		return $this->request->$key;
	}

	/**
	 * Call method for calling functions from the created request object 
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __call($method, $args = Array())
	{
		return call_user_func_array([$this->request, $method], $args);
	}

} // END class Request
