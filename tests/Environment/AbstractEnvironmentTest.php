<?php

use Wasp\Test\TestCase;
use Wasp\Environment\TestEnvironment;

/**
 * Test case for the abstract environment test
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class AbstractEnvironmentTest extends TestCase
{
    /**
     * Test building an environment
     *
     * @return void
     */
    public function test_build()
    {
        $env = new TestEnvironment();
        $env->load();

        $this->assertInstanceOf('Wasp\DI\DI', $env->getDI());
        $this->assertInstanceOf('Wasp\Application\Application', $env->getApplication());
    }

    /**
     * Test connecting to a database from the environment
     *
     * @return void
     */
    public function test_connectTo()
    {
        $env = new TestEnvironment();
        $env->load();

        $env->getDI()->get('connections')->add('default', [
            'driver'        => 'mysqli',
            'user'          => 'user',
            'debug'         => false
        ]);

        $env->settings['application']['default_connection'] = 'default';
        $env->connect();
    }

    /**
     * Test that the environment along with application and profile are added to the DI
     *
     * @return void
     */
    public function test_injections_after_load()
    {
        $env = new TestEnvironment();
        $env->load();

        $this->assertInstanceOf('Wasp\Application\Application', $env->getDI()->get('application'));
        $this->assertInstanceOf('Wasp\Application\Profile', $env->getDI()->get('profile'));
        $this->assertInstanceOf('Wasp\Environment\TestEnvironment', $env->getDI()->get('env'));
    }

    /**
     * Test that we can setup templates from the environment
     *
     * @return void
     */
    public function test_setup_templates()
    {
        $env = new TestEnvironment();
        $env->load();

        $env->setupTemplates(dirname(__DIR__) . '/Templating/Templates/');

        $copy = $env->getDI()->get('template')->make('twigtest.html.twig');

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $copy);
    }

} // END class AbstractEnvironmentTest extends TestCase
