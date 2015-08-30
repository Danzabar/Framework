<?php

namespace Wasp\Database;

/**
 * The schema class, uses Doctrine's Schema Tools
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Schema
{

    /**
     * A connected instance of the Connection class
     *
     * @var Object
     */
    protected $connection;

    /**
     * The Factory responsible for metadata classes
     *
     * @var Object
     */
    protected $metadataFactory;

    /**
     * An instance of the Doctrine schema tool class
     *
     * @var Object
     */
    protected $schema;

    /**
     * An Array of metadata classes
     *
     * @var Object
     */
    protected $classes;

    /**
     * Set up dependencies
     *
     * @param Wasp\Database\Connection $connection
     * @author Dan Cox
     */
    public function __construct($connection)
    {
        // Save objects
        $this->connection = $connection;
        $this->schema = $connection->getSchemaTool();
        $this->metadataFactory = $connection->connection()->getMetadataFactory();
    }

    /**
     * Loads the metadata for all classes
     *
     * @return Array
     * @author Dan Cox
     */
    public function loadMetaData()
    {
        return $this->classes = $this->metadataFactory->getAllMetadata();
    }

    /**
     * Create the Schema for the current connection
     *
     * @return void
     * @author Dan Cox
     */
    public function create()
    {
        $this->schema->createSchema($this->loadMetaData());
    }

    /**
     * Returns the SQL to update the schema
     *
     * @return Array
     * @author Dan Cox
     */
    public function getSql()
    {
        return $this->schema->getUpdateSchemaSql($this->loadMetaData());
    }

    /**
     * Updates the Schema Based on its entity classes for this connection
     *
     * @return void
     * @author Dan Cox
     */
    public function update()
    {
        $this->schema->updateSchema($this->loadMetaData(), true);
    }

    /**
     * Drops all the tables
     *
     * @return void
     * @author Dan Cox
     */
    public function dropTables()
    {
        $this->schema->dropSchema($this->loadMetaData());
    }

    /**
     * Drops tables for given classes
     *
     * @param Array|String $classes
     * @return void
     * @author Dan Cox
     */
    public function dropTable($classes)
    {
        $this->schema->dropSchema($classes);
    }

    /**
     * Drops the database
     *
     * @author Dan Cox
     */
    public function dropDatabase()
    {
        $this->schema->dropDatabase();
    }

} // END class Schema
