<?php

namespace Wasp\Database;

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
     * An Array of directories where entity models reside
     *
     * @var Array
     */
    protected $defaultModelDirectories;

    /**
     * Set up class vars
     *
     */
    public function __construct()
    {
        $this->connection = new \STDClass;
        $this->defaults();

        $this->typeMap = array(
            'Array'         => 'convertFromArray'
        );
    }

    /**
     * Creates a default object which can be extended
     *
     * @return void
     */
    public function defaults()
    {
        $this->connection->details = array(
            'driver'        => 'pdo_mysql',
            'user'          => 'root',
            'password'      => '',
            'memory'        => false,
            'dbname'        => ''
        );

        $this->connection->models = array();
        $this->connection->debug = true;
    }

    /**
     * Loads a raw connection and type
     *
     * @param Mixed $raw - the raw configuration data
     * @param String $type - the type of configuration
     * @return Object
     */
    public function load($raw, $type = 'Array', $defaultModelDirectories = array())
    {
        $this->raw = $raw;
        $this->type = $type;
        $this->defaultModelDirectories = $defaultModelDirectories;

        $this->map($type, 'Wasp\Exceptions\Database\InvalidConnectionType');
        return $this->connection;
    }

    /**
     * Converts the array into usable blocks of data using the STD class.
     *
     * @return void
     */
    public function convertFromArray()
    {
        // Map the details
        $this->mapDetailsFromArray();

        // Models Directory
        if (isset($this->raw['models'])) {
            $this->setModelsDirectory($this->raw['models']);
        }

        // Adding default model directories
        $this->appendDefaultModelDirectories();

        // Check if debug is set
        $this->connection->debug = (isset($this->raw['debug']) ? $this->raw['debug'] : true);
    }

    /**
     * Appends the default model directories
     *
     * @return void
     */
    public function appendDefaultModelDirectories()
    {
        foreach ($this->defaultModelDirectories as $md) {
            $this->connection->models[] = $md;
        }
    }

    /**
     * Sets the models directory
     *
     * @return void
     */
    public function setModelsDirectory($dir)
    {
        if (!is_array($dir)) {
            $dir = array($dir);
        }

        $this->connection->models = $dir;
    }

    /**
     * Maps details from the raw variable to the connection
     *
     * @return void
     */
    public function mapDetailsFromArray()
    {
        foreach (array_keys($this->connection->details) as $key) {
            if (array_key_exists($key, $this->raw)) {
                $this->connection->details[$key] = $this->raw[$key];
            }
        }
    }
} // END class ConnectionValidator
