<?php

use Wasp\Test\TestCase,
    Wasp\DI\ServiceMockery;

/**
 * Test case for the response class
 *
 * @package Wasp
 * @subpackage Tests\Controller
 * @author Dan Cox
 */
class ResponseTest extends TestCase
{
    /**
     * Set up test env
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        $mock = new ServiceMockery('response.file');
        $mock->add();

        parent::setUp();
    }

    /**
     * Create a response
     *
     * @return void
     * @author Dan Cox
     */
    public function test_CreateResponse()
    {
        $res = $this->DI->get('response');
        
        $response = $res->createResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    /**
     * No description needed.
     *
     * @return void
     * @author Dan Cox
     */
    public function test_createVariousResponses()
    {
        $res = $this->DI->get('response');

        $success = $res->make('Success', 200);
        $auth = $res->make('UnAuthorized', 400);
        $notFound = $res->make('NotFound', 404);
        $changed = $res->make('Changed', 202);

        $this->assertEquals('Success', $success->getContent());
        $this->assertEquals(200, $success->getStatusCode());

        $this->assertEquals('UnAuthorized', $auth->getContent());
        $this->assertEquals(400, $auth->getStatusCode());

        $this->assertEquals('NotFound', $notFound->getContent());
        $this->assertEquals(404, $notFound->getStatusCode());

        $this->assertEquals('Changed', $changed->getContent());
        $this->assertEquals(202, $changed->getStatusCode());

    }

    /**
     * Test creating a json response
     *
     * @return void
     * @author Dan Cox
     */
    public function test_jsonResponse()
    {
        $res = $this->DI->get('response');
        $result = ['status' => 'success', 'data' => ['test' => 'foo']];

        $json = $res->json($result, 200);

        $this->assertEquals($result, json_decode($json->getContent(), true));
        $this->assertEquals(200, $json->getStatusCode());
    }

    /**
     * Basic test for binary file data responses
     *
     * @return void
     * @author Dan Cox
     */
    public function test_binaryFileResponse()
    {
        $this->DI->get('response.file')->shouldReceive('setFile')->with('path/to/file.txt');

        $res = $this->DI->get('response');
        $binary = $res->file('path/to/file.txt');
    }

    /**
     * Test creating a jsonp response
     *
     * @return void
     * @author Dan Cox
     */
    public function test_jsonpResponse()
    {
        $res = $this->DI->get('response');

        $jsonp = $res->jsonp('Handler', ['foo' => 'bar'], 202);

        $this->assertContains("Handler", $jsonp->getContent());
        $this->assertEquals(202, $jsonp->getStatusCode());
    }

    /**
     * Test persisting input using the session manager class
     *
     * @return void
     * @author Dan Cox
     */
    public function test_persistingInput()
    {
        $this->DI->get('route')
                 ->add('input.test', '/test', Array('POST'), Array('_controller' => 'Wasp\Test\Controller\Controller::redirectWithInput'));

        $response = $this->fakeRequest('/test', 'POST', Array('foo' => 'bar'));

        $input = unserialize(base64_decode($this->DI->get('session')->get('input\old')));   

        $this->assertTrue($this->DI->get('session')->has('input\old'));     
        $this->assertEquals('bar', $input->get('foo'));
    }

} // END class ResponseTest extends TestCase
