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
     * Test the respond function
     *
     * @return void
     * @author Dan Cox
     */
    public function test_respond()
    {
        $app = $this->application;

        // Add a route
        $DI = $app->getDI();

        $DI->get('route')->add('test.route', '/', Array('GET'), Array('_controller' => 'Wasp\Test\Controller\Controller::returnObject'));
        $DI->get('request')->make('/', 'GET', []);

        ob_start();

        $app->respond();

        $response = ob_get_contents();

        ob_end_clean();

        $this->assertEquals('Foo', $response);
    }

} // END class ApplicationTest extends TestCase
