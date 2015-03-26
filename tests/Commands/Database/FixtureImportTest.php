<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery,
	Symfony\Component\Console\Tester\CommandTester;

/**
 * Test Class for the Fixture Import command
 *
 * @package Wasp
 * @subpackage Tests\Commands
 * @author Dan Cox
 */
class FixtureImportTest extends TestCase
{
	
	/**
	 * Array of commands used by the test
	 *
	 * @var Array
	 */
	protected $commands = [
		'Wasp\Commands\Database\FixtureImport'
	];

	/**
	 * Setup test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$mock = new ServiceMockery('fixtures');
		$mock->add();

		parent::setUp();

		$this->DI->get('commandloader')->fromArray($this->commands);
	}

	/**
	 * Test just running a fixture import
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_runImportWithoutDirectory()
	{
		$fixtures = $this->DI->get('fixtures');
		$command = $this->DI->get('console')->find('fixture:import');

		$fixtures->shouldReceive('load')->once();
		$fixtures->shouldReceive('import')->once();
	
		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName()
		]);

		$this->assertContains('Success', $CT->getDisplay());
	}

	/**
	 * Test adding the directory parameter as well
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_runImportAfterChangingDirectory()
	{	
		$fixtures = $this->DI->get('fixtures');
		$command = $this->DI->get('console')->find('fixture:import');

		$fixtures->shouldReceive('setDirectory')->with('test')->once();
		$fixtures->shouldReceive('load')->once();
		$fixtures->shouldReceive('import')->once();
	
		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName(),
			'directory'		=> 'test'
		]);

		$this->assertContains('Success', $CT->getDisplay());

	}

} // END class FixtureImportTest extends TestCase
