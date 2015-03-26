<?php namespace Wasp\Commands\Database;

use Wasp\Commands\BaseCommand;

/**
 * Command class for fixture purging
 *
 * @package Wasp
 * @subpackage Commands\Database
 * @author Dan Cox
 */
class FixturePurge extends BaseCommand
{

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected $name = 'fixture:purge';	

	/**
	 * Command Description
	 *
	 * @var string
	 */
	protected $description = 'Uses the fixture manager to run the purge method';

	/**
	 * Setup command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->argument('directory', 'Specify a new directory to load fixtures', 'optional');
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
		$FM->purge();
		$this->output->writeln("Successfully purged fixtures");
	}
	
} // END class FixturePurge extends BaseCommand
