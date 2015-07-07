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
	 * An Array of Compiler Passes used by this test
	 *
	 * @var Array
	 */
	protected $passes = [
		'Wasp\DI\Pass\DatabaseMockeryPass'
	];

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

		// Setup the DB
		$this->DI->get('database')->create(ENTITIES);
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

		$this->assertEquals(4, count($form->fields()));
		$this->assertContains('<form action="/form/test" method="POST" class="form">', $form->open(['class' => 'form']));
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
	 * Test failure due to csrf token
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_csrfFail()
	{
		$this->setExpectedException('Wasp\Exceptions\Forms\InvalidCSRFToken');

		$this->DI->get('request')->make('/form', 'POST', []);

		$form = new Wasp\Test\Forms\Forms\TestForm();
		$form->open();
		$form->validate();
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

	/**
	 * Test validating a form
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_validation()
	{
		$this->DI->get('request')->make('/form', 'POST', Array());

		$form = new Wasp\Test\Forms\Forms\TestForm();
		$result = $form->validate();

		$this->assertFalse($result);
	}

	/**
	 * Check the checkbox group output from our test form
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_checkboxGroupOutput()
	{
		$request = $this->DI->get('request')->make('/form', 'POST', Array('checkgroup' => 'Y'));

		$form = new Wasp\Test\Forms\Forms\TestForm();
		$fields = $form->fields();

		$checkgroup = $fields[2];

		$this->assertEquals('Y', $checkgroup->getValue());
		$this->assertEquals('<label><input type="checkbox" name="checkgroup" value="Y" checked="checked"/>Yes</label><label><input type="checkbox" name="checkgroup" value="N" />No</label>', $checkgroup->field());
	}

	/**
	 * Test binding model data to the form
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_modelBindingForm()
	{
		$form = new Wasp\Test\Forms\Forms\TestModelForm();

		$fields = $form->fields();
		$name = $fields[0];

		$this->assertEquals('Dan', $name->getValue());
	}	

	/**
	 * Test that input from the request has higher priority than the model data
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_userInputShouldOverrideModelData()
	{
		$request = $this->DI->get('request')->make('/form', 'POST', Array('name' => 'Bob'));

		$form = new Wasp\Test\Forms\Forms\TestModelForm();

		$fields = $form->fields();
		$name = $fields[0];

		$this->assertEquals('Bob', $name->getValue());
	}


} // END class FormTest extends TestCase
