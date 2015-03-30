<?php

use Wasp\Test\TestCase;

/**
 * Test Case for the twig engine class
 *
 * @package Wasp
 * @subpackage Tests\Templating
 * @author Dan Cox
 */
class TwigEngineTest extends TestCase
{

	/**
	 * Test the exists function
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fileexists()
	{
		$this->DI->get('template')->setDirectory(__DIR__ . '/Templates/');
		$twig = $this->DI->get('twigengine');

		$this->assertFalse($twig->exists('test.twig'));
		$this->assertTrue($twig->exists('twigtest.html.twig'));
	}

} // END class TwigEngineTest extends TestCase
