<?php

use Wasp\Test\TestCase;

/**
 * Test case for the console application
 *
 * @package Waso
 * @subpackage Tests\Console
 * @author Dan Cox
 */
class ConsoleApplicationTest extends TestCase
{

	/**
	 * Some assertions on the console Application
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function test_createApplicationInstance()
	{
		$console = $this->DI->get('console');

		// It should be a Symfony console application
		$this->assertInstanceOf('Symfony\Component\Console\Application', $console);
	}

	/**
	 * Check that commands added have a DI instance
	 *
	 * @return void
	 * @author Dan Cox
	 **/
	public function test_addedCommandsShouldHaveDIAccess()
	{
		$console = $this->DI->get('console');

		$console->add(new Wasp\Test\Commands\Commands\TestCommand);

		$command = $console->find('test:command');

		$this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $command->getDI());	
	}
	
} // END class ConsoleApplicationTest extends TestCase
