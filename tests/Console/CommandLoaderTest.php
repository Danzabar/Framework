<?php

use Wasp\Test\TestCase;

/**
 * Test Case for the Command Loader
 *
 * @package Wasp
 * @subpackage Tests\Console
 * @author Dan Cox
 */
class CommandLoaderTest extends TestCase
{

	/**
	 * Load commands by array
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_loadByArray()
	{
		$loader = $this->DI->get('commandloader');
		$loader->fromArray([
			'Wasp\Test\Commands\Commands\TestCommand'
		]);

		// We should be able to find this command by its name
		$app = $this->DI->get('console');
		$command = $app->find('test:command');

		$this->assertInstanceOf('Wasp\Test\Commands\Commands\TestCommand', $command);
	}

} // END class CommandLoaderTest extends TestCase
