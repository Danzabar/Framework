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
			->option('add', 'Adds an available module and namespace to the list file', 'none')
			->option('remove', 'Removes the available module from the list', 'none', 'r')
			->option('activate', 'A flag that specifies whether we should activate this module', 'none', 'a')
			->option('deactivate', 'A flag that specifies whether we should deactivate this module', 'none', 'd')
			->option('list', 'Lists out modules, works with --only-active and --only-inactive flags', 'none', 'l')
			->option('only-active', 'When listing modules this flag only shows active modules', 'none')
			->option('only-inactive', 'When lisitng modules this flag only shows inactive modules', 'none');
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

		$this->module = $this->input->getArgument('module');
		$this->namespace = $this->input->getOption('namespace');

		$router = $this->DI->get('command.router');
		$router->loadObject($this);
		$router->addRoutes([
			'activate'		=> 'activateModule',
			'add'			=> 'addModule',
			'list'			=> 'listModules',
			'deactivate'	=> 'deactivateModule',
			'remove'		=> 'removeModule'
		])->route($this->input);
	}

	/**
	 * Activates a module using the manager
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function activateModule()
	{
		$this->manager->activate($this->module);	
		$this->output->writeln(sprintf("Activated %s successfully", $this->module));
	}

	/**
	 * Adds a module to available file list
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function addModule()
	{
		$this->manager->add($this->module, $this->namespace);
		$this->output->writeln("Saved new module: ". $this->module);
	}

	/**
	 * Removes the module from the available list
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function removeModule()
	{
		$this->manager->remove($this->module);
		$this->output->writeln("Removed module: ". $this->module);
	}

	/**
	 * Deactivates a module using the manage
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function deactivateModule()
	{
		$this->manager->deactivate($this->module);
		$this->output->writeln("Deactivated module: ". $this->module);
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

		$value = ($this->input->getOption('only-active') === true ? true : false);

		foreach ($modules as $module => $ns)
		{
			if ($cache->has($module) == $value)
			{
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
