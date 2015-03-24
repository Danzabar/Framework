<?php namespace Wasp\Test\Commands\Commands;

use Wasp\Commands\BaseCommand;

/**
 * A Test Command
 *
 */
class TestCommand extends BaseCommand
{

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected $name = 'test:command';

	/**
	 * Command description
	 *
	 * @var string
	 */
	protected $description = 'Just a test command';

	/**
	 * Setup command 
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{

	}

	/**
	 * Fire command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function fire()
	{

	}
	
} // END class TestCommand extends BaseCommand
