<?php

use Wasp\Forms\Field,
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

} // END class FieldTest extends TestCase
