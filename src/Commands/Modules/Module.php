<?php namespace Wasp\Commands\Modules;

use Wasp\Commands\BaseCommand;

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
			->argument('module', 'The module name', 'required')
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

				break;
			case ($this->input->getOption('deactivate')):

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
	
} // END class Module extends BaseCommand
