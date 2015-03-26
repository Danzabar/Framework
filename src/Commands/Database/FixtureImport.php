<?php namespace Wasp\Commands\Database;

use Wasp\Commands\BaseCommand;

/**
 * Command for importing database fixtures
 *
 * @package Wasp
 * @subpackage Commands\Database
 * @author Dan Cox
 */
class FixtureImport extends BaseCommand
{

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected $name = 'fixture:import';

	/**
	 * Command description
	 *
	 * @var string
	 */
	protected $description = 'Imports fixtures';

	/**
	 * Set up command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->argument('directory', 'Specify a new directory to load fixtures from', 'optional');
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

		if (!is_null($this->input->getArgument('directory')))
		{
			$FM->setDirectory($this->input->getArgument('directory'));	
		}

		$FM->load();
		$FM->import();
		$this->output->writeln("Successfully ran database fixtures");
	}
	
		
} // END class FixtureImport extends BaseCommand
