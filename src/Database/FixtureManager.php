<?php

namespace Wasp\Database;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * Loads and runs fixture classes
 *
 * @package Wasp
 * @subpackage Fixtures
 * @author Dan Cox
 */
class FixtureManager
{

    /**
     * The directory of the fixtures
     *
     * @var string
     */
    protected $directory;

    /**
     * Instance of the connection class
     *
     * @var Object
     */
    protected $connection;

    /**
     * Instance of Doctrine fixture executor
     *
     * @var Object
     */
    protected $executor;

    /**
     * Instance of the Directory Loader
     *
     * @var Object
     */
    protected $loader;

    /**
     * Fixture list from the Directory loader
     *
     * @var Array
     */
    protected $fixtureList;

    /**
     * Set up class dependencies
     *
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->executor = new ORMExecutor($this->connection->connection(), new ORMPurger());
    }

    /**
     * Loads fixtures from the directory
     *
     * @return void
     */
    public function load()
    {
        $this->loader = new Loader;
        $this->loader->loadFromDirectory($this->directory);
        $this->fixtureList = $this->loader->getFixtures();
    }

    /**
     * Run import method on loaded fixtures
     *
     * @param Boolean $append - Appends the fixtures rather than purging first.
     * @return void
     */
    public function import($append = false)
    {
        $this->executor->execute($this->fixtureList, $append);
    }

    /**
     * Gets the value of directory
     *
     * @return String
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Sets the value of directory
     *
     * @param String $directory The directory that contains fixtures
     *
     * @return FixtureManager
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }
} // END class FixtureManager
