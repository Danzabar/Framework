<?php namespace Wasp\Database;

use Wasp\Exceptions\Database\InvalidConnection,
	Wasp\DI\DependencyInjectionAwareTrait;

/**
 * The Collection class for Database Connections
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class ConnectionCollection
{
	use DependencyInjectionAwareTrait;

	/**
	 * An Array of Connection Configurations
	 *
	 * @var Array
	 */
	protected $connections;

	/**
	 * Set up class defaults
	 *
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->connections = Array();
	}

	/**
	 * Loads a configuration
	 *
	 * @param String $name - The name of the connection
	 * @param Array $configuration - The database settings for this connection
	 * @return ConnectionCollection
	 * @author Dan Cox
	 */
	public function add($name, $configuration = Array(), $type = 'Array')
	{
		$this->connections[$name] = $this->DI->get('connection_validator')
											 ->load($configuration, $type);
	}

	/**
	 * Adds an array of connections
	 *
	 * @param Array $connections
	 * @param String $type
	 * @return ConnectionCollection
	 * @author Dan Cox
	 */
	public function addBulk(Array $connections, $type = 'Array')
	{
		foreach ($connections as $name => $configuration)
		{
			$this->add($name, $configuration, $type);
		}

		return $this;
	}

	/**
	 * Find a connection by name
	 *
	 * @param String $name - The name of the desired connection
	 * @return Array
	 * @author Dan Cox
	 */
	public function find($name)
	{
		if (array_key_exists($name, $this->connections))
		{
			return $this->connections[$name];
		}
		
		throw new InvalidConnection($name);
	}

} // END class CollectionCollection
