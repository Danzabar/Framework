<?php namespace Wasp\Database;

use Wasp\Utils\TypeMapTrait;

/**
 * The Connection Validator takes connections in multiple forms and returns a standardized object
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class ConnectionValidator
{
	use TypeMapTrait;

	/**
	 * The raw connection input
	 *
	 * @var Mixed
	 */
	protected $raw;

	/**
	 * The type of connection
	 *
	 * @var String
	 */
	protected $type;

	/**
	 * A STDClass with connection details
	 *
	 * @var Object
	 */
	protected $connection;

	/**
	 * Set up class vars
	 *
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->connection = new \STDClass;
		$this->defaults();
		
		$this->typeMap = Array(
			'Array'			=> 'convertFromArray'
		);
	}

	/**
	 * Creates a default object which can be extended
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function defaults()
	{
		$this->connection->details = Array(
			'driver'		=> 'pdo_mysql',
			'user'			=> 'root',
			'password'		=> '',
			'database'		=> ''
		);

		$this->connection->models = Array();
		$this->connection->debug = true;
	}

	/**
	 * Loads a raw connection and type
	 *
	 * @param Mixed $raw - the raw configuration data 
	 * @param String $type - the type of configuration
	 * @return Object
	 * @author Dan Cox
	 */
	public function load($raw, $type = 'Array')
	{
		$this->raw = $raw;
		$this->type = $type;

		$this->map($type, 'Wasp\Exceptions\Database\InvalidConnectionType');
		return $this->connection;
	}

	/**
	 * Converts the array into usable blocks of data using the STD class.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function convertFromArray()
	{
		// Setup the details array
		foreach (array_keys($this->connection->details) as $key) 
		{
			if (array_key_exists($key, $this->raw))
			{
				$this->connection->details[$key] = $this->raw[$key];		
			}
		}

		// Add model directories
		if (array_key_exists('models', $this->raw))
		{
			if (is_array($this->raw['models']))
			{
				$this->connection->models = $this->raw['models'];
			} else
			{
				$this->connection->models[] = $this->raw['models'];
			}
		}

		// Check if debug is set
		$this->connection->debug = (isset($this->raw['debug']) ? $this->raw['debug'] : true);
	}

} // END class ConnectionValidator
