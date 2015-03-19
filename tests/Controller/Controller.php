<?php namespace Wasp\Test\Controller;

use Wasp\Controller\BaseController;

/**
 * Test Controller
 *
 * @author Dan Cox
 */
class Controller extends BaseController
{

	/**
	 * Return a string response
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function returnString()
	{
		return 'Test';
	}

	/**
	 * Return a response object
	 *
	 * @return Response
	 * @author Dan Cox
	 */
	public function returnObject()
	{	
		return $this->response->make('Foo', 200);
	}

	/**
	 * Redirect
	 *
	 * @return Response
	 * @author Dan Cox
	 */
	public function redirect()
	{
		return $this->response->redirect('/');
	}
	
} // END class Controller extends BaseController
