<?php namespace Wasp\Modules;

use Wasp\Exceptions\Modules\MissingCacheSettings,
	Wasp\Utils\SectionSorterTrait,
	Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Controls cache files for Modules
 *
 * @package Wasp
 * @subpackage Modules
 * @author Dan Cox
 */
class ModuleCache
{
	use DependencyInjectionAwareTrait, SectionSorterTrait;

	/**
	 * Instance of the Config Class containing the JSON cache file
	 *
	 * @var \Danzabar\Config\Files\ConfigFile
	 */
	protected $cache;

	/**
	 * Collection of settings
	 *
	 * @var Wasp\Utils\Collection
	 */
	protected $settings;

	/**
	 * A collection that contains process cache elements
	 *
	 * @var Wasp\Utils\Collection
	 */
	protected $processed;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	protected $settingGroups = [
		'routes'			=> 'Routes',
		'entities'			=> 'Entities',
		'extensions'		=> 'Extensions',
		'commands'			=> 'Commands',
		'viewDirectory'		=> 'Views'
	];

	/**
	 * Attempts to grab the cache file
	 *
	 * @return \Danzabar\Config\Files\ConfigFile
	 * @throws \Wasp\Exceptions\Modules\MissingCacheSettings
	 * @author Dan Cox
	 */
	public function cache()
	{
		if ($this->settings->has('cache_file'))
		{
			$this->cache = $this->DI->get('config');
			$this->cache->init($this->settings->get('cache_file'));	
			return $this->cache;
		}

		throw new MissingCacheSettings('cache_file');
	}

	/**
	 * Checks whether a module is active
	 *
	 * @param String $module
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function has($module)
	{
		return isset($this->cache()->params()->$module);
	}

	/**
	 * Writes cache data to the cache file
	 *
	 * @return \Danzabar\Config\Files\ConfigFile
	 * @author Dan Cox
	 */
	public function write($module, Array $cacheData)
	{
		$this->cache()
			 ->params()
			 ->merge(Array($module => $cacheData));

		$this->cache->save();

		return $this->cache;
	}

	/**
	 * Removes module data from the cache file
	 *
	 * @return \Danzabar\Config\Files\ConfigFile
	 * @author Dan Cox
	 */
	public function remove($module)
	{
		unset($this->cache->params()->$module);

		$this->cache->save();

		return $this->cache;
	}

	/**
	 * Load settings
	 *
	 * @param \Wasp\Utils\Collection $settings
	 * @return ModuleCache
	 * @author Dan Cox
	 */
	public function load(\Wasp\Utils\Collection $settings)
	{
		$this->settings = $settings;
		return $this;
	}

	/**
	 * Processes the cache into sections to use in various parts of the framework
	 *
	 * @return Collection
	 * @author Dan Cox
	 */
	public function process()
	{
		$this->processed = new \Wasp\Utils\Collection();

		if (!is_null($this->cache) && !empty($this->cache->params()->all()))
		{
			$this->processed = $this->orderSections($this->cache->params()->all(), $this->settingGroups);	
		} 

		return $this->processed;
	}

	/**
	 * Returns the processed cache collection
	 *
	 * @return Wasp\Utils\Collection
	 * @author Dan Cox
	 */
	public function getProcessed()
	{	
		if (is_null($this->processed))
		{
			$this->process();
		}

		return $this->processed;
	}


	/**
	 * Returns the parambag
	 *
	 * @return Danzabar\Config\Data\Parambag
	 * @author Dan Cox
	 */
	public function data()
	{	
		return $this->cache->params();
	}
	
	
} // END class ModuleCache
