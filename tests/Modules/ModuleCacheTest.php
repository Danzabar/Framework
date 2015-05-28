<?php

use Wasp\Test\TestCase;

/**
 * Test Case for module cache class
 *
 * @package Wasp
 * @subpackage Tests\Modules
 * @author Dan Cox
 */
class ModuleCacheTest extends TestCase
{

	/**
	 * Setup test environment
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

		$this->DI->get('module.manager')->activate('test');
		$this->DI->get('module.cache')->process();
	}
	
	/**
	 * Test Processing the cache
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_processCache()
	{
		$collection = $this->DI->get('module.cache')->getProcessed();

		$this->assertEquals(1, count($collection->get('Routes')));
	}

	/**
	 * Test loading entity directories through the cache
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_entityDirectory()
	{
		/**
		 *	When we create a connection, with the current
		 *	Module active, we should have an extra Model 
		 *	Directory in the array
		 */	
		$this->DI->get('connections')->add('wasp', [
			'driver'			=> 'pdo_mysql',
			'user'				=> 'user',
			'models'			=> ENTITIES,
			'debug'				=> true
		]);	

		$connection = $this->DI->get('connections')->find('wasp');

		$this->assertEquals(2, count($connection->models));
	}

	/**
	 * Test adding a command through a module
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_command()
	{
		$console = $this->DI->get('console');	
		$console->loadCommandsFromModule();

		$command = $console->find('test:test');
		
		$this->assertEquals('test:test', $command->getName());	
	}

} // END class ModuleCacheTest extends TestCase

