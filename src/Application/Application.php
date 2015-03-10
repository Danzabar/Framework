<?php namespace Wasp\Application;

use Wasp\Application\DI;

/**
 * Application class
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class Application
{
	
	/**
	 * The core service file name
	 *
	 * @var string
	 */
	protected $coreServiceFile;

	/**
	 * The Directory where service files are stored
	 *
	 * @var string
	 */
	protected $DIDirectory;

	/**
	 * Instance of the DependencyInjection Class
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Sets default values for the DI and Application
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct()
	{
		// This ensures that even if no other config is specified
		// We use basic core framework
		$this->coreServiceFile = 'core';
		$this->DIDirectory = dirname(__DIR__) . '/Config/';
	}

	/**
	 * Builds an app container using the DI
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function build()
	{
		$this->DI = new DI($this->DIDirectory);
		$this->DI->build()->load($this->coreServiceFile);
	}

	/**
	 * Returns the DI Instance
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function getDI()
	{
		return $this->DI;
	}

	/**
	 * Sets the file name for core services
	 *
	 * @param String $file - the file name without extension
	 * @return Application
	 * @author Dan Cox
	 */
	public function setCoreServiceFile($file)
	{
		$this->coreServiceFile = $file;
		return $this;
	}

	/**
	 * Returns the current value for the core service file
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getCoreServiceFile()
	{
		return $this->coreServiceFile;
	}

	/**
	 * Sets the directory that the DI class looks in
	 *
	 * @param String $directory
	 * @return Application
	 * @author Dan Cox
	 */
	public function setDIDirectory($directory)
	{
		$this->DIDirectory = $directory;
		return $this;
	}

	/**
	 * Returns the value for the DI Directory var
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getDIDirectory()
	{
		return $this->DIDirectory;
	}

} // END class Application
