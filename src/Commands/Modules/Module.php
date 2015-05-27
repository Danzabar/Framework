<?php namespace Wasp\Commands\Modules;

use Wasp\Commands\BaseCommand,
	Symfony\Component\Console\Helper\Table;

/**
 * Command line class for Module interaction
 *
 * @package Wasp
 * @subpackage Commands\Modules
 * @author Dan Cox
 */
class Module extends BaseCommand
{

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected $name = 'wasp:modules';	

	/**
	 * Command description
	 *
	 * @var string
	 */
	protected $description = 'Activate and deactivate modules';

	/**
	 * Instance of the module manager class
	 *
	 * @var \Wasp\Modules\ModuleManager
	 */
	private $manager;

	/**
	 * Set up the command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this
			->argument('module', 'The module name', 'optional')
			->option('namespace', 'The namespace of the module class', 'optional', 'ns')
			->option('add', 'Adds an available module and namespace to the list file', 'optional')
			->option('remove', 'Removes the available module from the list', 'optional', 'r')
			->option('activate', 'A flag that specifies whether we should activate this module', 'optional', 'a')
			->option('deactivate', 'A flag that specifies whether we should deactivate this module', 'optional', 'd')
			->option('list', 'Lists out modules, works with --only-active and --only-inactive flags', 'optional', 'l')
			->option('only-active', 'When listing modules this flag only shows active modules', 'optional')
			->option('only-inactive', 'When lisitng modules this flag only shows inactive modules', 'optional');
	}

	/**
	 * Fires the command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function fire()
	{
		$this->manager = $this->DI->get('module.manager');	

		switch(true)
		{
			case ($this->input->getOption('activate')):
				
				return $this->activateModule($this->input->getArgument('module'));
				
				break;
			case ($this->input->getOption('add')):
				
				return $this->addModule($this->input->getArgument('module'), $this->input->getOption('namespace'));
				
				break;
			case ($this->input->getOption('list')):
				
				return $this->listModules();

				break;
			case ($this->input->getOption('deactivate')):
					
				return $this->deactivateModule($this->input->getArgument('module'));

				break;
			case ($this->input->getOption('remove')):

				return $this->removeModule($this->input->getArgument('module'));

				break;
		}
	}

	/**
	 * Activates a module using the manager
	 *
	 * @param String $module
	 * @return void
	 * @author Dan Cox
	 */
	public function activateModule($module)
	{
		$this->manager->activate($module);	
		$this->output->writeln("Activated $module successfully");
	}

	/**
	 * Adds a module to available file list
	 *
	 * @param String $module
	 * @param String $namespace
	 * @return void
	 * @author Dan Cox
	 */
	public function addModule($module, $namespace)
	{
		$this->manager->add($module, $namespace);
		$this->output->writeln("Saved new module: $module");
	}

	/**
	 * Removes the module from the available list
	 *
	 * @param String $module
	 * @return void
	 * @author Dan Cox
	 */
	public function removeModule($module)
	{
		$this->manager->remove($module);
		$this->output->writeln("Removed module: $module");
	}

	/**
	 * Deactivates a module using the manage
	 *
	 * @param String $module
	 * @return void
	 * @author Dan Cox
	 */
	public function deactivateModule($module)
	{
		$this->manager->deactivate($module);
		$this->output->writeln("Deactivated module: $module");
	}

	/**
	 * Returns an array of modules
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function getModulesByOptions()
	{
		$modules = $this->manager->asArray();
		$results = Array();
		$cache = $this->manager->getCache();	
		$value = null;

		switch(true)
		{
			case ($this->input->getOption('only-active')):
				
				$value = true;
				
				break;
			case ($this->input->getOption('only-inactive')):

				$value = false;

				break;
		}

		foreach ($modules as $module => $ns)
		{
			if (!is_null($value) && $cache->has($module) != $value)
			{
				unset($modules[$module]);
			} else {
				$results[] = [$module, $ns];		
			}
		}

		return $results;
	}

	/**
	 * Lists modules
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function listModules()
	{
		$modules = $this->getModulesByOptions();

		$table = new Table($this->output);
		$table->setHeaders(['Module Name', 'Module Namespace']);
		$table->setRows($modules);
		$table->render();
	}
	
} // END class Module extends BaseCommand
