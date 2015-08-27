<?php

use Wasp\Test\TestCase;


/**
 * Test Case for the Templating engines
 *
 * @package Wasp
 * @subpackage Tests\Templating
 * @author Dan Cox
 */
class TemplateTest extends TestCase
{

	/**
	 * Set up test class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$this->setupTemplates(__DIR__ . '/Templates/');
	}

	/**
	 * Test the twig engine
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_twigEngine()
	{
		$response = $this->DI->get('template')->make('twigtest.html.twig', ['foo' => 'bar']);
		$this->assertContains("twig test", $response->getContent());
		$this->assertContains("bar", $response->getContent());
	}

	/**
	 * Add both engines and test both templates
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_delegation()
	{
		$response = $this->DI->get('template')->make('twigtest.html.twig', ['foo' => 'bar']);

		$this->assertContains('twig test', $response->getContent());
	}

	/**
	 * Test that start function throws an exception if no directory is set
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_failOnStart()
	{
		$this->setExpectedException("Wasp\Exceptions\Templating\DirectoryNotSet");

		$this->DI->get('template')
				 ->clearDirectory()
				 ->start();
	}

	/**
	 * Test twigs ability to use the container
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_twigDI()
	{
		$this->DI->get('twigengine')->create(['strict_variables' => 'true']);

		$this->DI->get('template')
				 ->addEngine($this->DI->get('twigengine'))
				 ->start();

		$response = $this->DI->get('template')->make('twigDITest.html.twig');

		$this->assertContains($this->DI->get('template')->getDirectoryString(), $response->getContent());
	}

	/**
	 * Test the templates delagtion between directories
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_multiple_directories()
	{
		$this->setupTemplates([__DIR__ . '/Temp/', __DIR__ . '/Templates/']);	

		$response = $this->DI->get('template')->make('delegationtest.twig.html');

		$this->assertContains('test', $response->getContent());
	}


} // END class TemplateTest extends TestCase
