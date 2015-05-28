<?php namespace Wasp\Modules;

use Wasp\DI\DependencyInjectionAwareTrait,
	Wasp\Utils\Collection;

/**
 * Module builder class helps modules with actions such as adding routes and objects
 *
 * @package Wasp
 * @subpackage Modules
 * @author Dan Cox
 */
class ModuleBuilder
{
	use DependencyInjectionAwareTrait;
	
	/**
	 * A collection of build settings
	 *
	 * @var Collection
	 */
	protected $build;

	/**
	 * The default build arrays to use as a marker
	 *
	 * @var Array
	 */
	protected $defaultBuild = [
		'routes'			=> Array(),
		'entities'			=> Array(),
		'extensions'		=> Array(),
		'commands'			=> Array(),	
		'viewDirectory'		=> ''
	];

	/**
	 * Set up class dependencies
	 *
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->build = new Collection($this->defaultBuild);
	}

	/**
	 * Merges values into the correct collection slot
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function mergeValues($key, Array $values)
	{
		$merged = array_merge($this->build->get($key), $values);

		$this->build->add($key, $merged);
	}

	/**
	 * Loads a route file
	 *
	 * @return ModuleBuilder
	 * @author Dan Cox
	 */
	public function addRoutesFromFile($file)
	{
		$this->mergeValues('routes', [$file]);
		return $this;
	}

	/**
	 * Registers an array of extensions
	 *
	 * @param Array $extensions
	 * @return ModuleBuilder
	 * @author Dan Cox
	 */
	public function registerExtensions(Array $extensions)
	{
		$this->mergeValues('extensions', $extensions);
		return $this;
	}

	/**
	 * Register entity directories
	 *
	 * @param Array $directories
	 * @return ModuleBuilder
	 * @author Dan Cox
	 */
	public function registerEntityDirectories(Array $directories)
	{
		$this->build->add('entities', $directories);
		return $this;
	}

	/**
	 * Returns the build collection
	 *
	 * @return Collection
	 * @author Dan Cox
	 */
	public function build()
	{	
		return $this->build;
	}

} // END class ModuleBuilder
