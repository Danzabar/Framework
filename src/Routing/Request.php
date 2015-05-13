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
	 * Instance of the session management class
	 *
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
	protected $session;
	

	/**
	 * Load dependencies
	 *
	 * @param \Symfony\Component\HttpFoundation\Session\Session $session
	 * @author Dan Cox
	 */
	public function __construct(\Symfony\Component\HttpFoundation\Session\Session $session)
	{
		$this->session = $session;
	}

	/**
	 * Get the request object from the current global var
	 *
	 * @return SymRequest
	 * @author Dan Cox
	 */
	public function fromGlobals()
	{
		$this->request = SymRequest::createFromGlobals();
		$this->oldInput();

		return $this->request;
	}

	/**
	 * Loads input from the session, which has been persisted through the response class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function oldInput()
	{
		if ($this->session->has('input\old'))
		{
			$input = $this->session->get('input\old');
			$this->session->
			$deobsfucated = unserialize(base64_decode($input));
			$this->putInput($deobsfucated);
		}
	}

	/**
	 * Creates a request from given details 
	 *
	 * @param String $uri
	 * @param String $type
	 * @param Array $params
	 * @return SymRequest
	 * @author Dan Cox
	 */
	public function make($uri, $type = 'GET', $params = Array())
	{
		$this->request = SymRequest::create($uri, $type, $params);

		return $this->request;
	}

	/**
	 * Returns the current request
	 *
	 * @return \Symfony\Component\HttpFoundation\Request
	 * @author Dan Cox
	 */
	public function getRequest()
	{
		if (is_null($this->request))
		{
			return $this->fromGlobals();
		}

		return $this->request;
	}

	/**
	 * Returns input from the current request
	 *
	 * @return Symfony\Component\HttpFoundation\ParamBag
	 * @author Dan Cox
	 */
	public function getInput()
	{
		if ($this->request->isMethod('GET'))
		{
			return $this->request->query;
		}

		return $this->request->request;
	}

	/**
	 * Put input back into the correct Parambag
	 *
	 * @param \Symfony\Component\HttpFoundation\ParamBag $input
	 * @return Request
	 * @author Dan Cox
	 */
	public function putInput(\Symfony\Component\HttpFoundation\ParameterBag $input)
	{
		if ($this->request->isMethod('GET'))
		{
			$this->request->query->add($input->all());
		} else
		{
			$this->request->request->add($input->all());
		}

		return $this;
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
