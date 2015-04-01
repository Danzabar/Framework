<?php

use Wasp\Test\TestCase,
	Wasp\DI\ExtensionRegister;

/**
 * Test case for the extension register class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class ExtensionRegisterTest extends TestCase
{
	
	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$register = new ExtensionRegister;
		$register->loadFromArray(['Wasp\Test\DI\Extension\TestExtension']);

		parent::setUp();
	}

	/**
	 * Tear down test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function tearDown()
	{
		parent::tearDown();

		ExtensionRegister::clearExtensions();
	}

	/**
	 * Test that the services have been compiled
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_servicesAreThere()
	{
		$this->assertInstanceOf('Service', $this->DI->get('service'));
	}

} // END class ExtensionRegisterTest extends TestCase
