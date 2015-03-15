<?php namespace Wasp\Database;

/**
 * The database class interacts with the database through Doctrine ORM
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Database
{
	/**
	 * Instance of the Connection class
	 *
	 * @var Object
	 */
	public $connection;

	/**
	 * Relates to an entity object, its name.
	 *
	 * @var String
	 */
	protected $entity;

	/**
	 * Set up class vars
	 *
	 * @param Wasp\Database\Connection $connection 
	 * @author Dan Cox
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Sets the entity to be used by queries
	 *
	 * @param String $entity - An entities name
	 * @return Database
	 * @author Dan Cox
	 */
	public function setEntity($entity)
	{
		$this->entity = $entity;
		return $this;
	}

	/**
	 * Find an entity entry by its identifier
	 *
	 * @param Mixed $indentifier - An id that relates to this entities identifier
	 * @return Object
	 * @author Dan Cox
	 */
	public function find($identifier)
	{
		return $this->connection
					->connection()
					->find($this->entity, $identifier);
	}

	/**
	 * Returns the current entity
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getEntity()
	{
		return $this->entity;
	}

} // END class Database
