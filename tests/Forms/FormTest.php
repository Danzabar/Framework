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
	 * Set up test environment
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();
		
		// The route we use.
		$this->DI->get('route')->add('form.test', '/form/test', Array('GET'));
	}

	/**
	 * Test building a new form 
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_processForm()
	{
		$form = new Wasp\Test\Forms\Forms\TestForm();

		$this->assertEquals(3, count($form->fields()));
		$this->assertEquals('<form action="/form/test" method="POST" class="form">', $form->open(['class' => 'form']));
		$this->assertEquals('</form>', $form->close());
	}

	/**
	 * Test the default values
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_defaultValues()
	{
		$form = new Wasp\Test\Forms\Forms\TestForm();
		$fields = $form->fields();

		$username = $fields[0];

		$this->assertEquals('Dan', $username->getValue());
		$this->assertEquals('<input type="text" name="username" id="username" value="Dan" />', $username->field());
	}

	/**
	 * Test values that come from user input
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_inputValues()
	{
		$request = $this->DI->get('request')->make('/form', 'POST', Array('username' => 'Bob'));

		$form = new Wasp\Test\Forms\Forms\TestForm();
		$fields = $form->fields();

		$username = $fields[0];

		$this->assertEquals('Bob', $username->getValue());
	}


} // END class FormTest extends TestCase
