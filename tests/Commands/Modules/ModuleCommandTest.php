<?php

use Wasp\Test\TestCase,
	Symfony\Component\Console\Tester\CommandTester;

/**
 * Test Case for the Module Command Line Commands
 *
 * @package Wasp
 * @subpackage Tests\Commands
 * @author Dan Cox
 */
class ModuleCommandTest extends TestCase
{
	
	/**
	 * An array of commands to be loaded by CLoader
	 *
	 * @var Array
	 */
	protected $commands = [
		'Wasp\Commands\Modules\Module'
	];

	/**
	 * Instance of the command class
	 *
	 * @var \Wasp\Commands\Modules\Module
	 */
	protected $command;

	/**
	 * Set up module settings
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$settings = [
			'available_record'			=> MODULES . 'modules.json',
		   	'cache_file'				=> MODULES . 'cache.json'	
		];

		$this->DI->get('module.manager')
			->loadSettings($settings)
			->initFiles();
			
		$this->command = $this->DI->get('console')->find('wasp:modules');
	}

	/**
	 * Test activating through CLI
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_activate()
	{
		$CT = new CommandTester($this->command);
		$CT->execute([
			'command'		=> $this->command->getName(),
			'module'		=> 'test',
			'--activate'	=> true
		]);

		$this->assertContains('Activated test successfully', $CT->getDisplay());
	}

	/**
	 * Adding a module through the CLI
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_add()
	{
		$CT = new CommandTester($this->command);
		$CT->execute([
			'command'			=> $this->command->getName(),
			'module'			=> 'newmodule',
			'--namespace'		=> 'Wasp\Test\Modules\NewTest\Module',
			'--add'				=> true
		]);

		$this->assertContains('Saved new module', $CT->getDisplay());
	}

	/**
	 * Test removing through CLI
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_remove()
	{
		$CT = new CommandTester($this->command);
		$CT->execute([
			'command'		=> $this->command->getName(),
			'module'		=> 'newmodule',
			'--remove'		=> true
		]);

		$this->assertContains("Removed module", $CT->getDisplay());
	}

} // END class ModuleCommandTest extends TestCase
