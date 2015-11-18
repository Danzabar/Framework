<?php

namespace Wasp\Database;

use Wasp\Database\Database;

/**
 * Mockery class specific to the database.
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class DatabaseMockery
{

    /**
     * Instance of the schema class
     *
     * @var \Symfony\Component\DepenedencyInjection\ContainerBuilder
     */
    protected $DI;

    /**
     * Set up class dependencies
     *
     */
    public function __construct($DI)
    {
        $this->DI = $DI;
    }

    /**
     * Creates the standard sqlite connection
     *
     * @param String $entityDirectory
     * @return void
     */
    public function create($entityDirectory = '')
    {
        $this->DI->get('connections')->add('mock', [
            'db_name'       => 'wasp_testing',
            'driver'        => 'pdo_sqlite',
            'memory'        => true,
            'models'        => $entityDirectory
        ]);

        // Connect to this
        $this->DI->get('connection')->connect('mock');

        $this->database = new Database($this->DI->get('connection'), $this->DI->get('request'));

        $this->DI->get('schema')->update();
    }

    /**
     * Removes all the mocked rows and tables
     *
     * @return void
     */
    public function clearMockedData()
    {
        $this->DI->get('schema')->dropDatabase();
    }

    /**
     * Divert calls back to the database
     *
     * @return void
     */
    public function __call($method, $params = array())
    {
        return call_user_func_array([$this->database, $method], $params);
    }
} // END class DatabaseMockery
