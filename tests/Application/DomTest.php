<?php

use Wasp\Test\TestCase;

/**
 * Test class for dom cralwer tests
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class DomTest extends TestCase
{
	
	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$this->DI->get('route')->add('dom.test', '/dom/test', Array('GET'), Array('controller' => 'Wasp\Test\Controller\Controller::returnHtml'));	
	}

	/**
	 * Test assertions against the html
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_assertionOnHtml()
	{
		// Make a request
		$this->DI->get('request')->make('/dom/test', 'GET');

		// Respond
		$this->respond();

		// Make assertions
		$node = $this->crawler
					 ->filter('p')
					 ->reduce(function($node, $i) {
					 	return $node->text() == 'foo';
					 });

		$this->assertEquals(1, count($node));
	}

} // END class DomTests extends TestCase
