<?php

use Wasp\Test\TestCase,
	Symfony\Component\Console\Tester\CommandTester;

/**
 * Test case for base command and some test commands
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class CommandTest extends TestCase
{

	/**
	 * Returns an array of commands to load
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function loadCommands()
	{
		return [
			'Wasp\Test\Commands\Commands\TestCommand',
		];
	}

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$this->DI->get('commandloader')->fromArray($this->loadCommands());
	}

	/**
	 * Test the test command..
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_arguments()
	{
		$command = $this->DI->get('console')
							->find('test:command');	
		
		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName(),
			'test'			=> 'foo'
		]);

		$this->assertContains('foo', $CT->getDisplay());
	}

	/**
	 * Test setting the flag option on the test command..
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_options()
	{
		$command = $this->DI->get('console')
							->find('test:command');	
		
		$CT = new CommandTester($command);
		$CT->execute([
			'command'		=> $command->getName(),
			'test'			=> 'foo',
			'--flag'		=> true
		]);

		$this->assertContains('flag set', $CT->getDisplay());	
	}
	
} // END class CommandTest extends TestCase
