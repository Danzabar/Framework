<?php

use Wasp\Test\TestCase;
use Wasp\Environment\TestEnvironment;
use Wasp\Utils\Collection;
use \Mockery as m;

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
     * Mockery instance of the profile
     *
     * @var Profile
     */
    protected $profile;

    /**
     * An array representing the profile
     *
     * @var array
     */
    protected $settings;

    /**
     * A config collection
     *
     * @var Collection
     */
    protected $config;

    /**
     * Set up test env
     *
     * @return void
     */
    public function setUp()
    {
        $this->profile = m::mock('Profile');

        $this->config = new Collection([
            'profiles'      => ['host' => 'develop'],
            'config_files'  => ['application', 'database', 'templates']
        ]);

        $this->settings = array(
            'application'       => array('debug' => true),
            'extensions'        => array('Wasp\Test\DI\Extension\FilterExtension'),
            'templates'         => array(),
            'database'          => array(
                'connections'   => array(
                    'default'       => array(
                        'driver'    => 'pdo_mysql',
                        'user'      => 'user',
                        'password'  => '',
                        'dbname'    => 'wasp'
                    ),
                ),
            ),
        );

        parent::setUp();
    }

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

    /**
     * Test loading an environment with configuration set
     *
     * @return void
     */
    public function test_load_with_configuration()
    {
        $this->profile->shouldReceive('addProfiles')->once();
        $this->profile->shouldReceive('addFiles')->once();
        $this->profile->shouldReceive('settings')->once();
        $this->profile->shouldReceive('getSettings')->once()->andReturn($this->profile);
        $this->profile->shouldReceive('all')->once()->andReturn($this->settings);

        $env = new TestEnvironment($this->profile);
        $env->setConfig($this->config);

        $env->load();
        $env->setupConnections();

        // We should have a default connection;
        $connection = $this->DI->get('connections')->find('default');

        $env->buildCacheDI();
    }

} // END class AbstractEnvironmentTest extends TestCase
