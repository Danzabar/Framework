<?php namespace Wasp\Database;

/**
 * The Connection Validator takes connections in multiple forms and returns a standardized object
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class ConnectionValidator
{
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
	 * Type function mappings
	 *
	 * @var Array
	 */
	protected $typeMappings = [
		'Array'				=> 'convertFromArray'
	];

	/**
	 * Set up class vars
	 *
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->connection = new \STDClass;
		$this->defaults();
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
		if (array_key_exists($type, $this->typeMappings))
		{
			$this->raw = $raw;
			$this->type = $type;

			call_user_func([$this, $this->typeMappings[$type]]);		
			
			return $this->connection;
		}

		throw new InvalidConnectionType($type, $this->typeMappings);
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
		if (array_key_exists('debug', $this->raw))
		{
			$this->connection->debug = $this->raw['debug'];
		}
	}

} // END class ConnectionValidator