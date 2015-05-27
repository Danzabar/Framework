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
	}
	
	/**
	 * Test Processing the cache
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_processCache()
	{
		$cache = $this->DI->get('module.cache');
		$collection = $cache->process();

		$this->assertEquals(1, count($collection->get('Routes')));
		$this->assertEquals(0, count($collection->get('Extensions')));
	}

} // END class ModuleCacheTest extends TestCase

