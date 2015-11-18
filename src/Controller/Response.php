<?php

namespace Wasp\Controller;

use Wasp\DI\DependencyInjectionAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     */
    public function createResponse()
    {
        return $this->DI->get('response.api');
    }

    /**
     * Returns a json encoded response
     *
     * @param Array $content
     * @param Integer $code
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function json($content = array(), $code = 200)
    {
        $response = $this->DI->get('response.json');
        $response->setData($content);
        $response->setStatusCode($code);

        return $response;
    }

    /**
     * Returns a jsonp response
     *
     * @param String $handler - the name of the handler
     * @param Array $content
     * @param Integer $code
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function jsonp($handler, $content = array(), $code = 200)
    {
        $response = $this->json($content, $code);
        $response->setCallback($handler);

        return $response;
    }

    /**
     * Returns a filled response
     *
     * @param String $content
     * @param Integer $code
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function make($content = '', $code = 200)
    {
        $response = $this->createResponse();
        $response->setContent($content);
        $response->setStatusCode($code);

        return $response;
    }

    /**
     * Creates a binary file response
     *
     * @param String $file - Path to file
     * @return Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file($file)
    {
        $response = $this->DI->get('response.file');
        $response->setFile($file);
        return $response;
    }

    /**
     * Redirect to a url
     *
     * @param String $url
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($url)
    {
        return new RedirectResponse($url);
    }

    /**
     * Stores the current input in the session for the next request
     *
     * @return Response
     */
    public function persistInput()
    {
        $input = $this->DI->get('request')->getInput();
        $type = $this->DI->get('request')->isMethod('GET') ? 'query' : 'request';
        $session = $this->DI->get('session');

        $obsfucated = base64_encode(serialize($input));
        $session->set('input\old', $obsfucated);
        $session->set('input\old.type', $type);

        return $this;
    }
} // END class Response
