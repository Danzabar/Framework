<?php namespace Wasp\Test\Modules\Test;

use Wasp\Commands\BaseCommand;

/**
 * Test Command
 *
 * @package default
 * @subpackage default
 * @author Dan Cox
 */
class TestCommand extends BaseCommand
{

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected $name = 'test:test';	

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	protected $description = 'Test';

	/**
	 * Setup command
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{	
		
	}

} // END class TestCommand extends BaseCommand
