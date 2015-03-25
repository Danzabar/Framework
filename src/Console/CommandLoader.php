<?php namespace Wasp\Console;

use Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Command loader class loads commands into the console application in a variation of ways
 *
 * @package Wasp
 * @subpackage Console
 * @author Dan Cox
 */
class CommandLoader
{
	use DependencyInjectionAwareTrait;

	/**
	 * Loads commands listed as an array inside a file
	 *
	 * @param String $file
	 * @return void
	 * @author Dan Cox
	 */
	public function fromFile($file)
	{
		$data = require_once $file;

		$this->fromArray($data);
	}

	/**
	 * Loads commands from an array
	 *
	 * @param Array $commands
	 * @return void
	 * @author Dan Cox
	 */
	public function fromArray($commands)
	{
		foreach ($commands as $command)
		{
			$this->add($command);
		}	
	}

	/**
	 * Adds a single command instance
	 *
	 * @param String $command - A Fully Qualified class 
	 * @return void
	 * @author Dan Cox
	 */
	public function add($command)
	{
		$reflection = new \ReflectionClass($command);
		$instance = $reflection->newInstance();

		$this->DI->get('console')->add($instance);
	}

	
} // END class CommandLoader
