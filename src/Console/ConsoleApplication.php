<?php namespace Wasp\Console;

use Symfony\Component\Console\Application as SymfonyApplication,
	Symfony\Component\Console\Command\Command as SymfonyCommand,
	Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Application Class for the Console
 *
 * @package Wasp
 * @subpackage Console
 * @author Dan Cox
 */
class ConsoleApplication extends SymfonyApplication
{
	use DependencyInjectionAwareTrait;

	/**
	 * Adds a command into the application
	 *
	 * @param \Symfony\Component\Console\Command\Command $command
	 * @return void
	 * @author Dan Cox
	 */
	public function add(SymfonyCommand $command)
	{
		if (is_a($command, '\Wasp\Commands\BaseCommand'))
		{	
			$command->setDI($this->DI);
		}	

		parent::add($command);
	}

	/**
	 * Loads commands from the module cache file
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function loadCommandsFromModule()
	{
		$cache = $this->DI->get('module.cache')->getProcessed();

		if ($cache->has('Commands'))
		{
			$this->DI->get('command.loader')->fromArray($cache->get('Commands'));
		}
	}

} // END class ConsoleApplication extends SymfonyApplication
