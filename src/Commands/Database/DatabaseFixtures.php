<?php namespace Wasp\Commands\Database;

use Wasp\Commands\BaseCommand;

/**
 * Command class for importing and purging fixtures
 *
 * @package Wasp
 * @subpackage Commands\Database
 * @author Dan Cox
 */
class DatabaseFixtures extends BaseCommand
{

	/**
	 * Command Name
	 *
	 * @var String
	 */
	protected $name = 'database:fixtures';

	/**
	 * Command description
	 *
	 * @var String
	 */
	protected $description = 'Allows usage of the fixture manager to import and purge fixture sets.';

	/**
	 * Set up command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this
			->argument('directory', 'Set a different directory to use', 'optional')
			->option('purge', 'Flag to start the fixture purge');
	}

	/**
	 * Fire command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function fire()
	{
		$FM = $this->DI->get('fixtures');

		if(!is_null($this->input->getArgument('directory')))
		{
			$FM->setDirectory($this->input->getArgument('directory'));
		}
		
		$FM->load();

		if($this->input->getOption('purge'))
		{
			$FM->purge();
			$this->output->writeln("Successfully purged fixtures");
		} else
		{
			$FM->import();
			$this->output->writeln("Successfully imported fixtures");
		}	
	}

} // END class DatabaseFixtures extends BaseCommand
