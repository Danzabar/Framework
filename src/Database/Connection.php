<?php

namespace Wasp\Database;

use Wasp\Database\ConnectionCollection;
use Wasp\Utils\TypeMapTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

/**
 * The connection class represents a single database connection
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Connection
{
    use TypeMapTrait;

    /**
     * Instance of the Connection Collections class
     *
     * @var Object
     */
    protected $collection;

    /**
     * A connection configuration
     *
     * @var Array
     */
    protected $connection;

    /**
     * Instance of the entity manager
     *
     * @var Object
     */
    protected $entityManager;

    /**
     * Instance of the validator
     *
     * @var Wasp\Entity\Validation
     */
    protected $validator;

    /**
     * metadata setup derived from the Setup class
     *
     * @var Object
     */
    protected $setup;

    /**
     * Load the collection
     *
     */
    public function __construct(ConnectionCollection $collection, $validator)
    {
        $this->collection = $collection;
        $this->validator = $validator;

        $this->typeMap = array(
            'Annotation'        => 'createMetaDataFromAnnotation',
            'YAML'              => 'createMetaDataFromYAML',
            'XML'               => 'createMetaDataFromXML'
        );
    }

    /**
     * Attempts to connect to a database through a named connection
     *
     */
    public function connect($name, $type = 'Annotation')
    {
        $this->connection = $this->collection->find($name);
        $this->map($type, 'Wasp\Exceptions\Database\InvalidMetaDataType');
        $this->createEntityManager();
    }

    /**
     * Returns the entity manager
     *
     * @return EntityManager
     */
    public function connection()
    {
        return $this->entityManager;
    }

    /**
     * Creates an entity manager using the previously setup details
     *
     * @return void
     */
    public function createEntityManager()
    {
        $this->entityManager = EntityManager::create($this->connection->details, $this->setup);
    }

    /**
     * Creates a setup object using Annotation Metadata
     *
     * @return void
     */
    public function createMetaDataFromAnnotation()
    {
        $this->setup = Setup::createAnnotationMetadataConfiguration(
            $this->connection->models,
            $this->connection->debug
        );

        // More advanced annotation engine
        $this->setup->setMetadataDriverImpl(
            new AnnotationDriver(
                new CachedReader(
                    new AnnotationReader(),
                    new ArrayCache()
                ),
                $this->connection->models
            )
        );
    }

    /**
     * Creates a setup object using YAML Metadata
     *
     * @return void
     */
    public function createMetaDataFromYAML()
    {
        $this->setup = Setup::createYAMLMetadataConfiguration($this->connection->models, $this->connection->debug);
    }

    /**
     * Creates a setup object using XML Metadat
     *
     * @return void
     */
    public function createMetaDataFromXML()
    {
        $this->setup = Setup::createXMLMetadataConfiguration($this->connection->models, $this->connection->debug);
    }

    /**
     * Returns an instanced Schema Tool
     *
     * @return SchemaTool
     */
    public function getSchemaTool()
    {
        return new SchemaTool($this->entityManager);
    }
} // END class Connection
