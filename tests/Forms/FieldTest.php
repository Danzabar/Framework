<?php

use Wasp\Forms\Field,
	Wasp\Forms\Validation,
	Wasp\Test\TestCase;

/**
 * Test Case for the Field class
 *
 * @package Wasp
 * @subpackage Tests\Forms
 * @author Dan Cox
 */
class FieldTest extends TestCase
{
	/**
	 * Test building the field label
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildLabel()
	{
		$field = new Field('Testing', 'String');
		$field2 = new Field('Test2', 'String');
		
		$this->assertEquals('<label for="testing">Testing</label>', $field->label());
		$this->assertEquals('<label class="label" for="test2">Test2</label>', $field2->label(Array('class' => 'label')));
	}

	/**
	 * Test building the element involved in a string field
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildStringField()
	{
		$field = new Field('test1', 'String');
		$field2 = new Field('test2', 'String');

		$this->assertEquals('<input type="text" name="test1" id="test1" value="" />', $field->field());
		$this->assertEquals('<input type="text" name="test2" id="test2" value="" class="form-control"/>', $field2->field(['class' => 'form-control']));
	}

	/**
	 * Test building a select box
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_buildSelectBox()
	{
		$field = new Field('select', 'Select', [], '', Array('Foo' => 'foo', 'Bar' => 'bar'));

		$this->assertEquals('<select name="select" id="select" ><option value="foo">Foo</option><option value="bar">Bar</option></select>', $field->field());
	}

	/**
	 * Testing the requried validation rule
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_requiredFieldRule()
	{
		$field = new Field('rule', 'String', [new Validation\Required()], '', Array(), Array('rule' => ''));
		$field->validate();	

		$field2 = new Field('rule2', 'String', [new Validation\Required('The rule2 field is required')]);
		$field2->validate();

		$field3 = new Field('rule3', 'String', [new Validation\Required()], '', Array(), Array('rule3' => 'foo'));
		$field3->validate();

		$error = $field->errors()[0];
		$error2 = $field2->errors()[0];

		$this->assertEquals('This field is required', $error);
		$this->assertEquals('The rule2 field is required', $error2);
		$this->assertEquals(0, count($field3->errors()));
	}

	/**
	 * Test email validation
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_emailValidation()
	{
		$field = new Field('rule', 'String', [new Validation\Email()], '', Array(), Array('rule' => 'test'));
		$field2 = new Field('rule2', 'String', [new Validation\Email()], '', Array(), Array('rule2' => 'test@test.com'));

		$field->validate();
		$field2->validate();

		$error = $field->errors()[0];

		$this->assertEquals('A valid email was expected', $error);
		$this->assertEquals(0, count($field2->errors()));
	}

	/**
	 * Test the integer validation
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_intValidation()
	{
		$field = new Field('rule', 'String', [new Validation\Integer()], '', Array(), Array('rule' => 'five'));
		$field2 = new Field('rule2', 'String', [new Validation\Integer()], '', Array(), Array('rule2' => '5'));

		$field->validate();
		$field2->validate();

		$error = $field->errors()[0];

		$this->assertEquals('An integer was expected', $error);
		$this->assertEquals(0, count($field2->errors()));
	}

	/**
	 * Test the url validation rule
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_urlValidation()
	{
		$field = new Field('rule', 'String', [new Validation\URL]);
		$field2 = new Field('rule2', 'String', [new Validation\URL], '', Array(), Array('rule2' => 'http://www.google.com'));

		$field->validate();
		$field2->validate();

		$error = $field->errors()[0];

		$this->assertEquals('A valid URL was expected', $error);
		$this->assertEquals(0, count($field2->errors()));
	}

	/**
	 * Test the float validation
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_floatValidation()
	{
		$field = new Field('rule', 'String', [new Validation\Float], '', Array(), Array('rule' => 'fivepointtwo'));
		$field2 = new Field('rule2', 'String', [new Validation\Float], '', Array(), Array('rule2' => '5.2'));

		$field->validate();
		$field2->validate();

		$error = $field->errors()[0];

		$this->assertEquals('A float was expected', $error);
		$this->assertEquals(0, count($field2->errors()));
	}

	/**
	 * Test the regexp validation
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_regexpValidation()
	{
		$field = new Field('rule', 'String', [new Validation\Regex(null, '/[A-Za-z]{1,}/')], '', Array(), Array('rule' => ''));
		$field2 = new Field('rule2', 'String', [new Validation\Regex(null, '/[A-Za-z]{1,}/')], '', Array(), Array('rule2' => 'abc'));
		$field3 = new Field('rule3', 'String', [new Validation\Regex()]);

		$field->validate();
		$field2->validate();
		$field3->validate();

		$error = $field->errors()[0];

		$this->assertEquals('This value fails filter criteria', $error);
		$this->assertEquals(0, count($field2->errors()));
		$this->assertEquals(0, count($field3->errors()));
	}

} // END class FieldTest extends TestCase
