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
     * Returns some html to work with the dom crawler
     *
     * @return String
     * @author Dan Cox
     */
    public function returnHtml()
    {
        return $this->response->make('<html><head></head><body><div class="tester"><p>foo</p><p>bar</p></div></body></html>');
    }

    /**
     * Redirect with attached input
     *
     * @return Response
     * @author Dan Cox
     */
    public function redirectWithInput()
    {
        return $this->response->persistInput()->redirect('/');
    }

} // END class Controller extends BaseController
