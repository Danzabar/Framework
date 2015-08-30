<?php namespace Wasp\Application;

use Wasp\Environment\Environment,
    Wasp\Utils\Collection,
    Wasp\Exceptions\Application\UnknownEnvironment,
    Symfony\Component\HttpKernel\KernelEvents,
    Symfony\Component\HttpKernel\HttpKernel;

/**
 * Application class
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class Application
{
    /**
     * DI instance from the environment
     *
     * @var \Wasp\DI\DI
     **/
    protected $DI;

    /**
     * An environment instance
     *
     * @var Object
     */
    public $env;

    /**
     * A setup instance of the profile class
     *
     * @var \Wasp\Application\Profile
     **/
    public $profile;

    /**
     * Collection instance containing environments
     *
     * @var Wasp\Utils\Collection
     */
    protected $envCollection;

    /**
     * Set up Application Defaults
     *
     * @return void
     * @author Dan Cox
     */
    public function __construct($profile = NULL)
    {
        $this->profile = $profile;

        $this->envCollection = new Collection;

        // Default Environments
        $this->registerEnvironment('test', 'Wasp\Environment\Test');
    }

    /**
     * Start the http kernel events
     *
     * @return void
     * @author Dan Cox
     */
    public function react()
    {
        // Add filter listeners to the Kernel
        $dispatcher = $this->DI->get('kernel.dispatcher');
        $dispatcher->addListener(KernelEvents::REQUEST, [$this->DI->get('filter.listener'), 'beforeRequest']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$this->DI->get('filter.listener'), 'onResponse']);

        return $this->DI->get('kernel')->handle($this->DI->get('request')->getRequest());
    }

    /**
     * Responds to the current request
     *
     * @return void
     * @author Dan Cox
     **/
    public function respond()
    {
        $response = $this->react();

        $response->send();

        $this->DI->get('kernel')->terminate($this->DI->get('request')->getRequest(), $response);
    }

    /**
     * Loads an environment by name
     *
     * @param String $name - The name of the environment
     * @return void
     * @author Dan Cox
     */
    public function loadEnv($name)
    {
        // Get the Environment's class
        $class = $this->getEnvironment($name);

        // Create reflection and invoke a new instance
        $reflection = new \ReflectionClass($class);
        $instance = $reflection->newInstance();

        // Call the Load method;
        $this->env = $instance->load($this);
        $this->DI = $this->env->getDI();
    }

    /**
     * Registers an environment so it can be used in App start up.
     *
     * @param String $name - the name of the environment
     * @param String $class - fully qualified class name as a string
     * @return Application
     * @author Dan Cox
     */
    public function registerEnvironment($name, $class)
    {
        $this->envCollection->add($name, $class);
        return $this;
    }

    /**
     * Registers an array of environments
     *
     * @param Array $environments
     * @return Application
     * @author Dan Cox
     */
    public function registerEnvironments(Array $environments)
    {
        $this->envCollection->append($environments);

        return $this;
    }

    /**
     * Gets an environments class name by its label
     *
     * @param String $name - the name of the environment
     * @return String
     * @author Dan Cox
     */
    public function getEnvironment($name)
    {
        if ($this->envCollection->exists($name))
        {
            return $this->envCollection->get($name);
        }

        throw new UnknownEnvironment($name);
    }

    /**
     * Removes the registered environment from the Application.
     *
     * @param String $name - the environment name
     * @return Application
     * @author Dan Cox
     */
    public function deregisterEnvironment($name)
    {
        if ($this->envCollection->exists($name))
        {
            $this->envCollection->remove($name);
            return $this;
        }

        // Throw exception
        throw new UnknownEnvironment($name);
    }

    /**
     * Returns the DI instance
     *
     * @return \Wasp\DI\DI
     * @author Dan Cox
     */
    public function getDI()
    {
        return $this->DI;
    }

} // END class Application
