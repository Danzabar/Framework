<?php namespace Wasp\Database;

use Doctrine\Fixture\Loader\DirectoryLoader,
	Doctrine\Fixture\Executor,
	Doctrine\Fixture\Filter\ChainFilter,
	Doctrine\Fixture\Configuration;

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
	 * @author Dan Cox
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
		$this->executor = new Executor(new Configuration);
	}

	/**
	 * Loads fixtures from the directory
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function load()
	{
		$this->loader = new DirectoryLoader($this->directory);
		$this->fixtureList = $this->loader->load();
	}

	/**
	 * Run import method on loaded fixtures
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function import()
	{
		$this->executor->execute($this->loader, new ChainFilter(), Executor::IMPORT);
	}

	/**
	 * Runs the purge method on loaded fixtures
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function purge()
	{
		$this->executor->execute($this->loader, new ChainFilter(), Executor::PURGE);
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
