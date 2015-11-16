<?php

namespace Wasp\Filter;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Listener class for filters
 *
 * @package Wasp
 * @subpackage Filter
 * @author Dan Cox
 */
class FilterListener
{
    /**
     * Instance of the DependencyInjection class
     *
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $DI;

    /**
     * Event object
     *
     * @var Object
     */
    protected $event;

    /**
     * Set up class dependencies
     *
     */
    public function __construct($di)
    {
        $this->DI = $di;
    }

    /**
     * before request event
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @return void
     */
    public function beforeRequest(GetResponseEvent $event)
    {
        $this->handleEvent($event, 'before');
    }

    /**
     * Event listener for kernel.response
     *
     * @param Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     * @return void
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $this->handleEvent($event, 'after');
    }

    /**
     * Handles filter functionality, as to not duplicate code
     *
     * @param Object $event
     * @param String $filterType eg. before, after
     * @return void
     */
    public function handleEvent($event, $filterType)
    {
        $this->event = $event;

        $filters = $this->event->getRequest()->get($filterType);

        if (is_array($filters)) {
            $this->triggerFilters($filters);
        }
    }

    /**
     * Finds and fires filters
     *
     * @param Array $filters
     * @param Object $event
     * @return void
     */
    public function triggerFilters(Array $filters)
    {
        foreach ($filters as $filter) {
            $obj = $this->DI->get($filter);

            $this->event = $obj->filter($this->event);
        }
    }
} // END class FilterListener
