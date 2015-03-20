<?php namespace Wasp\Controller;


use Wasp\DI\DependencyInjectionAwareTrait,
	Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Response class deals with returning responses and redirections
 *
 * @package Wasp
 * @subpackage Controller\Response
 * @author Dan Cox
 */
class Response
{
	use DependencyInjectionAwareTrait;

	/**
	 * Creates a response using the response.api class
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @author Dan Cox
	 */
	public function createResponse()
	{
		return $this->DI->get('response_api');
	}

	/**
	 * Returns a json encoded response
	 *
	 * @param Array $content
	 * @param Integer $code
	 * @return Symfony\Component\HttpFoundation\Response
	 * @author Dan Cox
	 */
	public function json($content = Array(), $code = 200)
	{
		$response = $this->createResponse();
		$response->setContent(json_encode($content));
		$response->setStatusCode($code);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * Returns a filled response
	 *
	 * @param String $content
	 * @param Integer $code
	 * @return Symfony\Component\HttpFoundation\Response
	 * @author Dan Cox
	 */
	public function make($content = '', $code = 200)
	{
		$response = $this->createResponse();
		$response->setContent($content);
		$response->setStatusCode($code);

		return $response;
	}

	/**
	 * Redirect to a url
	 *
	 * @param String $url
	 * @return Symfony\Component\HttpFoundation\RedirectResponse
	 * @author Dan Cox
	 */
	public function redirect($url)
	{
		return new RedirectResponse($url);
	}
	
} // END class Response
