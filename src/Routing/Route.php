<?php

namespace Wasp\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;

/**
 * Adds routes and route groups to the collection
 *
 * @package Wasp
 * @subpackage Routing
 * @author Dan Cox
 **/
class Route
{
    /**
     * Route collection instance
     *
     * @var \Symfony\Component\Routing\RouteCollection
     **/
    protected $collection;

    /**
     * Instance of Symfony Collection
     *
     * @var Object
     **/
    protected $activeGroup;

    /**
     * An array of defaults for the current active route group
     *
     * @var Array
     */
    protected $activeGroupDefaults;

    /**
     * Route prefix
     *
     * @var string
     */
    protected $prefix;

    /**
     * An array of rest methods and values
     *
     * @var Array
     */
    private $typeMap = [
        'all'           => ['uri' => '', 'method' => ['GET']],
        'show'          => ['uri' => '/{id}', 'method' => ['GET']],
        'edit'          => ['uri' => '/edit/{id}', 'method' => ['GET']],
        'create'        => ['uri' => '/new', 'method' => ['POST']],
        'new'           => ['uri' => '/new', 'method' => ['GET']],
        'update'        => ['uri' => '/update/{id}', 'method' => ['PATCH']],
        'delete'        => ['uri' => '/delete/{id}', 'method' => ['DELETE']]
    ];

    /**
     * Load dependencies
     *
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     **/
    public function __construct($routeCollection)
    {
        $this->collection = $routeCollection;
    }

    /**
     * Creates a simple route
     *
     * @param String $name - the route name
     * @param String $uri
     * @param Array $methods
     * @param Array $defaults
     * @param Array $requirements
     * @param String $host
     * @param Array $schemes
     * @return void
     **/
    public function add(
        $name,
        $uri,
        $methods = array(),
        $defaults = array(),
        $requirements = array(),
        $host = '',
        $schemes = array()
    ) {
        if (!is_null($this->activeGroup)) {
            $defaults = array_merge($defaults, $this->activeGroupDefaults);
        }

        $route = new SymfonyRoute(
            (is_null($this->prefix) ? $uri : $this->prefix . $uri),
            $defaults,
            $requirements,
            array(),
            $host,
            $schemes,
            $methods
        );

        $this->processRoute($name, $route);
    }

    /**
     * Uses the standard Rest controller to provide a rest
     * structure for an entity
     *
     * @param String $name
     * @param String $uri
     * @param String $entity - The qualified entity class name
     * @param String $controller
     *
     * @return void
     */
    public function resource($name, $uri, $entity, $controller = 'Wasp\Controller\RestController')
    {
        /**
         *  We can use the "rest" method to create the routes
         *  And provide a default variable for the entity
         */
        $this->rest($name, $uri, $controller, ['all', 'show', 'update', 'create', 'delete'], ['entity' => $entity]);
    }

    /**
     * Creates a set of RESTful routes
     *
     * @param String $name
     * @param String $uri
     * @param String $action
     * @param Array $methods
     * @param Array $defaults
     *
     * @return void
     */
    public function rest($name, $uri, $action, $methods = array(), $defaults = array())
    {
        $methods = (!empty($methods) ? $methods : array('all', 'show', 'update', 'create', 'new', 'edit', 'delete'));

        foreach ($methods as $method) {
            $m = $this->typeMap[$method];

            $this->addRestRoute(
                $name .'.'. $method,
                $uri . $m['uri'],
                $action .'::'. $method,
                $m['method'],
                $defaults
            );
        }
    }

    /**
     * Adds a rest route to the collection
     *
     * @param String $name
     * @param String $uri
     * @param String $method
     * @param String $action
     * @param Array $defaults
     *
     * @return void
     */
    public function addRestRoute($name, $uri, $action, $method, $defaults = array())
    {
        $this->add($name, $uri, $method, array_merge(['_controller' => $action], $defaults));
    }

    /**
     * Adds a route to the appropriate collection
     *
     * @param String $name
     * @param SymfonyRoute $route
     * @return void
     **/
    public function processRoute($name, $route)
    {
        if (!is_null($this->activeGroup)) {
            $this->activeGroup->add($name, $route);

        } else {
            $this->collection->add($name, $route);
        }
    }

    /**
     * Adds a route group
     *
     * @param Array $defaults
     * @param Closure $callback
     * @return void
     **/
    public function group(Array $defaults, $callback)
    {
        $this->activeGroup = new \Symfony\Component\Routing\RouteCollection;
        $this->activeGroupDefaults = $defaults;
        $this->activeGroup->addDefaults($defaults);

        call_user_func_array($callback, [$this]);

        $this->addGroup();
        $this->prefix = null;
    }

    /**
     * Adds prefix to the current active group
     *
     * @param String $prefix
     * @return Route
     **/
    public function addPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Adds the current active group
     *
     * @return void
     **/
    public function addGroup()
    {
        $this->collection->addCollection($this->activeGroup);

        $this->activeGroup = null;
        $this->activeGroupDefaults = array();
    }
} // END class Route
