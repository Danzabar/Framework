<?php

namespace Wasp\Application;

use Wasp\Environment\Environment;
use Wasp\Utils\Collection;
use Wasp\Exceptions\Application\UnknownEnvironment;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernel;

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
     * A setup instance of the profile class
     *
     * @var \Wasp\Application\Profile
     **/
    public $profile;

    /**
     * Set up Application Defaults
     *
     * @return void
     */
    public function __construct($profile = null)
    {
        $this->profile = $profile;
    }

    /**
     * Start the http kernel events
     *
     * @return void
     */
    public function react()
    {
        $request = $this->DI->get('request')->getRequest();

        // Add filter listeners to the Kernel
        $dispatcher = $this->DI->get('kernel.dispatcher');
        $dispatcher->addListener(KernelEvents::REQUEST, [$this->DI->get('filter.listener'), 'beforeRequest']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$this->DI->get('filter.listener'), 'onResponse']);

        return $this->DI->get('kernel')->handle($request);
    }

    /**
     * Responds to the current request
     *
     * @return void
     **/
    public function respond()
    {
        $response = $this->react();

        $this->DI->get('request')->setCurrentRoute();

        $response->send();

        $this->DI->get('kernel')->terminate($this->DI->get('request')->getRequest(), $response);
    }

    /**
     * Returns the DI instance
     *
     * @return \Wasp\DI\DI
     */
    public function getDI()
    {
        return $this->DI;
    }

    /**
     * Sets the Dependency Injector instance
     *
     * @param Wasp\DI\DI
     * @return Application
     */
    public function setDI($DI = null)
    {
        $this->DI = $DI;
        return $this;
    }
} // END class Application
