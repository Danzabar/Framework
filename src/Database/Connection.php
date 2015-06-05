<?php namespace Wasp\Database;

use Wasp\Database\ConnectionCollection,
	Wasp\Utils\TypeMapTrait,
	Doctrine\ORM\EntityManager,
	Doctrine\ORM\Tools\SchemaTool,
	Doctrine\Common\Cache\ArrayCache,
	Doctrine\Common\Annotations\CachedReader,
	Doctrine\Common\Annotations\AnnotationReader,
	Doctrine\ORM\Mapping\Driver\AnnotationDriver,
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
	 * @author Dan Cox
	 */
	public function __construct(ConnectionCollection $collection, $validator)
	{
		$this->collection = $collection;
		$this->validator = $validator;

		$this->typeMap = Array(
			'Annotation'		=> 'createMetaDataFromAnnotation',
			'YAML'				=> 'createMetaDataFromYAML',
			'XML'				=> 'createMetaDataFromXML'
		);
	}

	/**
	 * Attempts to connect to a database through a named connection
	 *
	 * @author Dan Cox
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
	 * Returns an instanced Schema Tool
	 *
	 * @return SchemaTool
	 * @author Dan Cox
	 */
	public function getSchemaTool()
	{
		return new SchemaTool($this->entityManager);
	}

} // END class Connection
