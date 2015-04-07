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

		$this->DI->get('template')
				 ->setDirectory(__DIR__ . '/Templates/');
	}

	/**
	 * Test the php engine
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_phpEngine()
	{
		$php = $this->DI->get('phpengine');
		$php->create();
		
		$this->DI->get('template')
				 ->addEngine($php->getEngine())
				 ->start();

		$output = $this->DI->get('template')->make('phptest.php', ['foo' => 'bar']);
		$this->assertContains("PHP engine", $output);
		$this->assertContains("bar", $output);
	}

	/**
	 * Test the twig engine
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_twigEngine()
	{
		$twig = $this->DI->get('twigengine');
		$twig->create();

		$this->DI->get('template')
				 ->addEngine($twig)
				 ->start();

		$output = $this->DI->get('template')->make('twigtest.html.twig', ['foo' => 'bar']);
		$this->assertContains("twig test", $output);
		$this->assertContains("bar", $output);
	}

	/**
	 * Add both engines and test both templates
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_delegation()
	{
		$this->DI->get('twigengine')->create();
		$this->DI->get('phpengine')->create();

		$this->DI->get('template')
				 ->addEngine($this->DI->get('twigengine'))
				 ->addEngine($this->DI->get('phpengine')->getEngine())
				 ->start();

		$php = $this->DI->get('template')->make('phptest.php', ['foo' => 'bar']);
		$twig = $this->DI->get('template')->make('twigtest.html.twig', ['foo' => 'bar']);

		$this->assertContains('PHP engine', $php);
		$this->assertContains('twig test', $twig);
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
				 ->setDirectory(null)
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

		$temp = $this->DI->get('template')->make('twigDITest.html.twig');
	
		$this->assertContains($this->DI->get('template')->getDirectory(), $temp);
	}
	

} // END class TemplateTest extends TestCase
