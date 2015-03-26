<?php

use Wasp\Test\TestCase,
	Wasp\DI\ServiceMockery,
	Symfony\Component\Console\Tester\CommandTester;

/**
 * Test case for the fixture purge command
 *
 * @package Wasp
 * @subpackage Tests\Commands
 * @author Dan Cox
 */
class FixturePurgeTest extends TestCase
{
	/**
	 * Array of commands that the test uses
	 *
	 * @var Array
	 */
	protected $commands = [
		'Wasp\Commands\Database\FixturePurge'
	];
	
	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$mock = new ServiceMockery('fixtures');
		$mock->add();

		parent::setUp();
	}

	/**
	 * Purge without setting the directory
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_purgeWithoutDirectory()
	{
		$fixtures = $this->DI->get('fixtures');
		$command = $this->DI->get('console')->find('fixture:purge');

		$fixtures->shouldReceive('load')->once();
		$fixtures->shouldReceive('purge')->once();

		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName()
		]);

		$this->assertContains("Success", $CT->getDisplay());
	}

	/**
	 * Purge with directory set
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_purgeWithDirectory()
	{
		$fixtures = $this->DI->get('fixtures');
		$command = $this->DI->get('console')->find('fixture:purge');

		$fixtures->shouldReceive('setDirectory')->with('Test')->once();
		$fixtures->shouldReceive('load')->once();
		$fixtures->shouldReceive('purge')->once();

		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName(),
			'directory'		=> "Test"
		]);

		$this->assertContains("Success", $CT->getDisplay());
	}

} // END class FixturePurgeTest extends TestCase

