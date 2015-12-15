<?php

use Wasp\Application\Application;
use Wasp\DI\ServiceMockery;
use Wasp\Test\TestCase;

/**
 * Test Case for the Application Class
 *
 * @package Wasp
 * @subpackage Tests\Application
 * @author Dan Cox
 */
class ApplicationTest extends TestCase
{
    /**
     * Set up test env
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->DI->get('route')->add('test.route', '/', Array('GET'), Array('_controller' => 'Wasp\Test\Controller\Controller::returnObject'));
    }

    /**
     * Test the respond function
     *
     * @return void
     * @author Dan Cox
     */
    public function test_respond()
    {
        $app = $this->application;

        // Add a route
        $DI = $this->DI;

        $DI->get('request')->make('/', 'GET', []);

        ob_start();

        $app->respond();

        $response = ob_get_contents();

        ob_end_clean();

        $this->assertEquals('Foo', $response);
    }

    /**
     * Test that it sets the current route name on a request
     *
     * @return void
     */
    public function test_it_sets_the_current_route_name()
    {
        $app = $this->application;

        $this->DI->get('request')->make('/', 'GET', []);

        ob_start();

            $app->respond();

        ob_end_clean();

        $this->assertEquals('test.route', $this->DI->get('request')->route);
    }

    /**
     * Test that you can access dependencies through the application object
     *
     * @return void
     */
    public function test_it_has_DI_access()
    {
        $this->assertInstanceOf('Wasp\DI\DI', $this->application->getDI());
    }

} // END class ApplicationTest extends TestCase
