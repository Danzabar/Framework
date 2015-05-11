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
	 * Returning a json response
	 *
	 * @return Response
	 * @author Dan Cox
	 */
	public function jsonResponse()
	{
		return $this->response->json(Array('1','2','3','4','5'), 200);
	}

	/**
	 * Test controller method that forwards to a new controller
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function forwardResponse()
	{
		return $this->forward('Wasp\Test\Controller\ForwardedController::forward', Array('id' => 2));
	}

	/**
	 * Returns some html to work with the dom crawler
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function returnHtml()
	{
		return '<html><head></head><body><div class="tester"><p>foo</p><p>bar</p></div></body></html>';
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
