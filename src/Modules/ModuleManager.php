<?php namespace Wasp\Modules;

use Wasp\Utils\Collection,
	Danzabar\Config\Files\ConfigFile,
	Wasp\Utils\SectionSorterTrait;

/**
 * Manages active and available modules
 *
 * @package Wasp
 * @subpackage Modules
 * @author Dan Cox
 */
class ModuleManager
{
	use SectionSorterTrait;

	/**
	 * A collection of available modules
	 *
	 * @var Collection
	 */
	protected $available;

	/**
	 * The file associated with the available modules list
	 *
	 * @var ConfigFile
	 */
	protected $availableFile;

	/**
	 * A collection of active modules
	 *
	 * @var Collection
	 */
	protected $active;

	/**
	 * Instance of the Module Cache
	 *
	 * @var Wasp\Modules\ModuleCache
	 */
	protected $cache;

	/**
	 * Instance of the Module Builder
	 *
	 * @var Wasp\Modules\ModuleBuilder
	 */
	protected $builder;

	/**
	 * A collection of settings
	 *
	 * @var Collection
	 */
	private $settings;

	/**
	 * An array of Setting groups
	 *
	 * @var Array
	 */
	private $settingGroups = [
		'Record'			=> ['available_record'],
		'Cache'				=> ['cache_file']
	];

	/**
	 * Set up class env
	 *
	 * @param Wasp\Modules\ModuleCache $cache
	 * @param Wasp\Modules\ModuleBuilder $builder
	 * @author Dan Cox
	 */
	public function __construct($cache, $builder)
	{
		$this->cache = $cache;
		$this->builder = $builder;
	}

	/**
	 * Creates config files for the available and active records
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function initFiles()
	{
		$record = $this->settings->get('Record');	

		if ($record->has('available_record'))
		{
			$this->availableFile = new ConfigFile();
			$this->availableFile->init($record->get('available_record'));

			$this->available = new Collection($this->availableFile->params()->modules);
		}	
	}

	/**
	 * Activates a module by its name
	 *
	 * @param String $module - The module name
	 * @return Boolean
	 * @throws Wasp\Exceptions\Modules\UnknownModule
	 * @author Dan Cox
	 */
	public function activate($module)
	{
		// Does the module exist
		if ($this->available->has($module))
		{
			$details = $this->available->get($module);

			$reflection = new \ReflectionClass($details);
			
			$method = $reflection->getMethod('setup');	
			$method->invokeArgs($reflection->newInstance(), ['builder' => $this->builder]);

			$this->cacheModuleBuild($module);

			return true;
		}

		throw new \Wasp\Exceptions\Modules\UnknownModule($module);
	}

	/**
	 * Caches the results of building a module
	 *
	 * @param String $module
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function cacheModuleBuild($module)
	{
		$arr = $this->builder->build();
		$this->cache->write($module, $arr->all());
	}

	/**
	 * Loads module based settings
	 *
	 * @param Array $settings
	 * @return ModuleManager
	 * @author Dan Cox
	 */
	public function loadSettings(Array $settings)
	{
		$this->settings = $this->sortSections($settings, $this->settingGroups);
		$this->cache->load($this->settings->get('Cache'));
	}

	/**
	 * Returns the settings collection
	 *
	 * @return Collection
	 * @author Dan Cox
	 */
	public function getSettings()
	{
		return $this->settings;
	}

	/**
	 * Gets the builder instance
	 *
	 * @return Wasp\Modules\ModuleBuilder
	 * @author Dan Cox
	 */
	public function getBuilder()
	{
		return $this->builder;
	}

	/**
	 * Returns the cache object associated with this manager
	 *
	 * @return Wasp\Modules\ModuleCache
	 * @author Dan Cox
	 */
	public function getCache()
	{	
		return $this->cache;
	}
	
} // END class ModuleManager
