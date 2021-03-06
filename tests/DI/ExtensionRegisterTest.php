<?php

use Wasp\Test\TestCase,
    Wasp\DI\Extension,
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
     * Test that the services have been compiled
     *
     * @return void
     * @author Dan Cox
     */
    public function test_servicesAreThere()
    {
        $this->assertInstanceOf('Service', $this->DI->get('service'));
    }

    /**
     * Does nothing, just for coverage
     *
     * @return void
     * @author Dan Cox
     */
    public function test_XsdValidationPath()
    {
        $extension = new Extension;
        $extension->getXsdValidationBasePath();
    }

    /**
     * Test loading extensions from a file
     *
     * @return void
     * @author Dan Cox
     */
    public function test_loadFromFile()
    {
        $register = new ExtensionRegister;
        $register->clearExtensions();
        $register->loadFromFile(__DIR__ . '/Extension/Extensions.php');

        $this->assertContains('Wasp\Test\DI\Extension\TestExtension', $register->getExtensions());
    }


} // END class ExtensionRegisterTest extends TestCase
