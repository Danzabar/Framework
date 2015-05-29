<?php namespace Wasp\Templating;

use Symfony\Component\Templating\DelegatingEngine,
	Wasp\Exceptions\Templating\DirectoryNotSet;

/**
 * Template class
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class Template
{
	/**
	 * Instance of the Delegation Engine
	 *
	 * @var Symfony\Component\Templating\DelegatingEngine
	 */
	protected $delegator;

	/**
	 * The directories that templates are kept in
	 *
	 * @var Array
	 */
	protected $directory;

	/**
	 * Instance of the Module Cache Class
	 *
	 * @var \Wasp\Modules\ModuleCache
	 */
	protected $cache;

	/**
	 * An Array of available template engines
	 *
	 * @var Array
	 */
	protected $engines = [];

	/**
	 * Load class dependencies
	 *
	 * @author Dan Cox
	 */
	public function __construct($cache)
	{
		$this->cache = $cache;
	}

	/**
	 * Creates a delegating engine.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function start()
	{
		if (empty($this->directory) || is_null($this->directory))
		{
			throw new DirectoryNotSet;
		}

		$this->delegator = new DelegatingEngine($this->engines);
	}

	/**
	 * Renders the template from its name with the appropriate engine
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function make($template, $params = Array())
	{
		return $this->delegator->render($template, $params);
	}

	/**
	 * Loads template directories from the module cache
	 *
	 * @return Template
	 * @author Dan Cox
	 */
	public function loadDirectoriesFromModules()
	{
		$processed = $this->cache->getProcessed();

		if ($processed->has('Views'))
		{
			$this->directory = array_merge($this->directory, $processed->get('Views'));		
		}

		return $this;
	}

	/**
	 * Sets the template directory
	 *
	 * @param String $directory
	 * @return Template
	 * @author Dan Cox
	 */
	public function setDirectory($directory)
	{
		$this->directory[] = $directory;
		return $this;
	}

	/**
	 * Clears the current values for the directory array
	 *
	 * @return Template
	 * @author Dan Cox
	 */
	public function clearDirectory()
	{
		$this->directory = Array();
		return $this;
	}

	/**
	 * Returns the template directory
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * Returns the directory array as a string
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getDirectoryString()
	{
		return join($this->directory, ',');
	}
	
	/**
	 * Adds an engine to the array
	 *
	 * @return Template
	 * @author Dan Cox
	 */
	public function addEngine($engine)
	{	
		$this->engines[] = $engine;
		return $this;
	}


} // END class Template
