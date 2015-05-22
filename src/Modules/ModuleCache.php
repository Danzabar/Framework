<?php namespace Wasp\Modules;

use Wasp\Exceptions\Modules\MissingCacheSettings,
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
	use DependencyInjectionAwareTrait;

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
	 * Attempts to grab the cache file
	 *
	 * @return \Danzabar\Config\Files\ConfigFile
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
