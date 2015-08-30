<?php

use Wasp\Test\TestCase;

/**
 * Test case for the URL generator class
 *
 * @package Wasp
 * @subpackage Tests\Routing
 * @author Dan Cox
 **/
class URLTest extends TestCase
{

    /**
     * Setup test env
     *
     * @return void
     * @author Dan Cox
     **/
    public function setUp()
    {
        parent::setUp();

        // Add a new route;
        $this->DI->get('route')->add('test.url', '/url', Array('controller' => 'Test::Test'));
        $this->DI->get('route')->add('test.post', '/url', Array('controller' => 'Test::post'));
    }

    /**
     * Test generating url from a route name
     *
     * @return void
     * @author Dan Cox
     **/
    public function test_generateFromRoute()
    {   
        $url = $this->DI->get('url');

        $this->assertEquals('/url', $url->route('test.url'));
        $this->assertEquals('/url', $url->route('test.post'));
    }

} // END class URLTest extends TestCase
