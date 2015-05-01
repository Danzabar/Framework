<?php

use Wasp\Test\TestCase;

/**
 * Test case for the form classes
 *
 * @package Wasp
 * @subpackage Tests/Form
 * @author Dan Cox
 */
class FormTest extends TestCase
{
	/**
	 * Test processing a form
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_processForm()
	{
		$form = new Wasp\Test\Forms\Forms\TestForm();
		$form->configure();	
	}


} // END class FormTest extends TestCase
