<?php

use Wasp\Test\TestCase,
	Wasp\Modules\ModuleBuilder;

/**
 * Test case for the module builder class
 *
 * @package Wasp
 * @subpackage Tests\Modules
 * @author Dan Cox
 */
class ModuleBuilderTest extends TestCase
{
	
	/**
	 * Test adding route files
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addRouteAsFile()
	{
		$builder = $this->DI->get('module.builder');

		$builder->addRoutesFromFile('/var/www/example.php');
		$buildArr = $builder->build();

		$this->assertEquals(1, count($buildArr->get('routes')));		
	}

	/**
	 * Test registering extensions through the builder
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_registerExtensions()
	{
		$builder = $this->DI->get('module.builder');

		$builder->registerExtensions(['/var/www/example']);
		$build = $builder->build();
		
		$this->assertEquals(1, count($build->get('extensions')));
	}	

} // END class ModuleBuilderTest extends TestCase
