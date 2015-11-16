<?php

namespace Wasp\Database;

use Wasp\Exceptions\Database\InvalidConnection;
use Wasp\DI\DependencyInjectionAwareTrait;
use Wasp\Utils\Collection;

/**
 * The Collection class for Database Connections
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class ConnectionCollection extends Collection
{
    use DependencyInjectionAwareTrait;

    /**
     * Model directories determined from modules
     *
     * @var Array
     */
    protected $modelDirectories = [];

    /**
     * Set up class defaults
     *
     */
    public function __construct()
    {
        $this->collectable = array();
    }

    /**
     * Loads a configuration
     *
     * @param String $name - The name of the connection
     * @param Array $configuration - The database settings for this connection
     * @return ConnectionCollection
     */
    public function add($name, $configuration = array(), $type = 'Array')
    {
        parent::add(
            $name,
            $this->DI->get('connection.validator')
                     ->load($configuration, $type, $this->modelDirectories)
        );
    }

    /**
     * Adds an array of connections
     *
     * @param Array $connections
     * @param String $type
     * @return ConnectionCollection
     */
    public function addBulk(Array $connections, $type = 'Array')
    {
        foreach ($connections as $name => $configuration) {
            $this->add($name, $configuration, $type);
        }

        return $this;
    }

    /**
     * Find a connection by name
     *
     * @param String $name - The name of the desired connection
     * @return Array
     */
    public function find($name)
    {
        if ($this->exists($name)) {
            return $this->get($name);
        }

        throw new InvalidConnection($name);
    }
} // END class CollectionCollection
