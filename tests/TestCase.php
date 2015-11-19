<?php namespace Wasp\Test;

use Wasp\DI\DICompilerPassRegister;
use Wasp\Environment\TestEnvironment;
use Wasp\DI\ExtensionRegister;
use Wasp\DI\ServiceMockery;
use Wasp\DI\ServiceMockeryLibrary;
use Symfony\Component\DomCrawler\Crawler;


/**
 * Test case base that provides application access
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Instance of the WASP Application
     *
     * @var Object
     */
    protected $application;

    /**
     * An array of compiler passes to register once the DI is created
     *
     * @var Array
     */
    protected $passes;

    /**
     * An array of mocks that will be auto-added to the service-mockery library on load
     *
     * @var Array
     */
    protected $mocks;

    /**
     * An array of extension classes that will be loaded before the DI is compiled
     *
     * @var Array
     */
    protected $extensions;

    /**
     * The environment class that should be used
     *
     * @var String
     */
    protected $environment;

    /**
     * Instance of the WASP DI
     *
     * @var Object
     */
    protected $DI;

    /**
     * The response
     *
     * @var Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * Instance of the Dom Crawler
     *
     * @var Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     * Set up test class
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        $this->registerPasses();
        $this->registerMocks();
        $this->registerExtensions();

        $this->loadEnvironment();

        if(property_exists($this, 'commands') && is_array($this->commands))
        {
            $this->DI->get('command.loader')->fromArray($this->commands);
        }
    }

    /**
     * Loads environment if set, if not uses the test environment
     *
     * @return void
     */
    public function loadEnvironment()
    {
        if (!is_null($this->environment)) {
            $refl = new \ReflectionClass($this->environment);
            $environment = $refl->newInstance();
        } else {
            $environment = new TestEnvironment();
        }

        $environment->load();
        $this->application = $environment->getApplication();
        $this->DI = $environment->getDI();
    }

    /**
     * Loads routes file from directory
     *
     * @return void
     */
    public function loadRoutes($dir)
    {
        $route = $this->DI->get('route');
        require ($dir . 'Routes.php');
    }

    /**
     * Uses the application to build a response object based on the current state of the request class
     *
     * @return void
     * @author Dan Cox
     */
    public function respond()
    {
        $this->response = $this->application->react();

        // Create the crawler for any html assertions
        $this->crawler = new Crawler($this->response->getContent());
    }

    /**
     * Sets up the template classes
     *
     * @param String|Array $directory
     * @return void
     * @author Dan Cox
     */
    public function setupTemplates($directory)
    {
        $this->DI->get('template')->setDirectory($directory);

        $this->DI->get('twigengine')->create();
        $this->DI->get('template')
                 ->addEngine($this->DI->get('twigengine'))
                 ->start();
    }

    /**
     * Fakes a request and returns the response
     *
     * @param String $uri
     * @param String $method
     * @param Array $params
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Dan Cox
     */
    public function fakeRequest($uri, $method, Array $params = Array())
    {
        $this->DI->get('request')->make($uri, $method, $params);

        $this->respond();

        return $this->response;
    }

    /**
     * Registers passes specified in the test
     *
     * @return void
     * @author Dan Cox
     */
    public function registerPasses()
    {
        if (!is_null($this->passes))
        {
            DICompilerPassRegister::add($this->passes);
        }
    }

    /**
     * Registers the mocks present in the Mock property
     *
     * @return void
     * @author Dan Cox
     */
    public function registerMocks()
    {
        if (!is_null($this->mocks))
        {
            foreach ($this->mocks as $m)
            {
                $mockery = new ServiceMockery($m);
                $mockery->add();
            }
        }
    }

    /**
     * Registers extensions
     *
     * @return void
     * @author Dan Cox
     */
    public function registerExtensions()
    {
        if (!is_null($this->extensions))
        {
            $register = new ExtensionRegister;
            $register->loadFromArray($this->extensions);
        }
    }

    /**
     * Tear down test class
     *
     * @return void
     * @author Dan Cox
     */
    public function tearDown()
    {
        \Mockery::close();

        // Clear the mocks
        $library = new ServiceMockeryLibrary;
        $library->clear();

        $extensions = new \Wasp\DI\ExtensionRegister;
        $extensions->clearExtensions();

        DICompilerPassRegister::clear();
    }

} // END class TestCase extends \PHPUnit_Framework_TestCase
