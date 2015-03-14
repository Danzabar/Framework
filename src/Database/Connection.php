<?php namespace Wasp\Database;

use Wasp\Database\ConnectionCollection,
	Wasp\Exceptions\Database\InvalidMetaDataType,
	Doctrine\ORM\EntityManager,
	Doctrine\ORM\Tools\Setup;

/**
 * The connection class represents a single database connection
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Connection
{
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
	 * metadata setup derived from the Setup class
	 *
	 * @var Object
	 */
	protected $setup;

	/**
	 * Mapped metadata strings to class functions
	 *
	 * @var Array
	 */
	protected $ConnectionTypeMap = [
		'Annotation'		=> 'createMetaDataFromAnnotation',
		'YAML'				=> 'createMetaDataFromYAML',
		'XML'				=> 'createMetaDataFromXML',
		'None'				=> 'createConfiguration'
	];

	/**
	 * Load the collection
	 *
	 * @author Dan Cox
	 */
	public function __construct(ConnectionCollection $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * Attempts to connect to a database through a named connection
	 *
	 * @author Dan Cox
	 */
	public function connect($name, $type = 'Annotation')
	{
		if (array_key_exists($type, $this->ConnectionTypeMap))
		{
			$this->connection = $this->collection->find($name);
			
			call_user_func([$this, $this->ConnectionTypeMap[$type]]);

			$this->createEntityManager();			
		}
		
		// Invalid connection type
		throw new InvalidMetaDataType($name, $this->ConnectionTypeMap);
	}

	/**
	 * Returns the entity manager
	 *
	 * @return EntityManager
	 * @author Dan Cox
	 */
	public function connection()
	{
		return $this->entityManager;
	}

	/**
	 * Creates an entity manager using the previously setup details
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function createEntityManager()
	{
		$this->entityManager = EntityManager::create($this->connection->details, $this->setup);
	}

	/**
	 * Creates a setup object using Annotation Metadata
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function createMetaDataFromAnnotation()
	{
		$this->setup = Setup::createAnnotationMetadataConfiguration($this->connection->models, $this->connection->debug);
	}

	/**
	 * Creates a setup object using YAML Metadata
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function createMetaDataFromYAML()
	{
		$this->setup = Setup::createYAMLMetadataConfiguration($this->connection->models, $this->connection->debug);
	}

	/**
	 * Creates a setup object using XML Metadat
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function createMetaDataFromXML()
	{
		$this->setup = Setup::createXMLMetadataConfiguration($this->connection->models, $this->connection->debug);
	}

	/**
	 * Creates a basic configuration with no metadata
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function createConfiguration()
	{
		$this->setup = Setup::createConfiguration($this->connection->debug);
	}


} // END class Connection