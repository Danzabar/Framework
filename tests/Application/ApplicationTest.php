<?php 

use Wasp\Application\Application,
    Wasp\DI\ServiceMockery;

/**
 * Test Case for the Application Class
 *
 * @package Wasp
 * @subpackage Tests\Application
 * @author Dan Cox
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test registering a test environment
     *
     * @return void
     * @author Dan Cox
     */
    public function test_registeringEnvironment()
    {
        $app = new Application;
        $app->registerEnvironment('TestEnv', 'TestEnvClass');

        $this->assertEquals('TestEnvClass', $app->getEnvironment('TestEnv'));
    }

    /**
     * Test registering an array of environments
     *
     * @return void
     * @author Dan Cox
     */
    public function test_registerEnvironments()
    {
        $envs = Array('TestEnv' => 'TestEnvClass');
        $app = new Application;
        $app->registerEnvironments($envs);

        $this->assertEquals('TestEnvClass', $app->getEnvironment('TestEnv'));
    }

    /**
     * Test registering and deregistering environments
     *
     * @return void
     * @author Dan Cox
     */
    public function test_deregisterEnvironment()
    {
        $this->setExpectedException('Wasp\Exceptions\Application\UnknownEnvironment');

        $app = new Application;
        $app->registerEnvironment('TestEnv', 'TestEnvClass');

        $this->assertEquals('TestEnvClass', $app->getEnvironment('TestEnv'));

        $app->deregisterEnvironment('TestEnv');

        $app->getEnvironment('TestEnv');
    }

    /**
     * Test deregistering an unknown environment
     *
     * @return void
     * @author Dan Cox
     */
    public function test_deregisterFail()
    {
        $this->setExpectedException('Wasp\Exceptions\Application\UnknownEnvironment');

        $app = new Application;
        $app->deregisterEnvironment('TestEnv');
    }

    /**
     * Test the failing of get environment
     *
     * @return void
     * @author Dan Cox
     */
    public function test_getEnvironmentFail()
    {
        $this->setExpectedException("Wasp\Exceptions\Application\UnknownEnvironment");

        $app = new Application;
        $app->getEnvironment("None");
    }

    /**
     * Test loading an environment
     *
     * @return void
     * @author Dan Cox
     */
    public function test_loadEnvironment()
    {
        $app = new Application;
        $app->loadEnv('test');

        $this->assertInstanceOf('Wasp\DI\DI', $app->env->getDI());
        $this->assertInstanceOf('Symfony\Component\Filesystem\Filesystem', $app->env->getDI()->get('fs'));
    }

    /**
     * Test the respond function
     *
     * @return void
     * @author Dan Cox
     */
    public function test_respond()
    {
        $app = new Application;
        $app->loadEnv('test');

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
    
} // END class ApplicationTest extends \PHPUnit_Framework_TestCase
