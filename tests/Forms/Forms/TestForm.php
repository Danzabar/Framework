<?php namespace Wasp\Test\Forms\Forms;

use Wasp\Forms\Form;

/**
 * Form class
 *
 * @package Wasp
 * @subpackage Tests\Forms
 * @author Dan Cox
 */
class TestForm extends Form
{
	/**
	 * Username field
	 *
	 * @var Array
	 */
	public $username = Array(
		'name'		 => 'Username',
		'type'		 => 'String',
		'rules'		 => Array()
	);

	/**
	 * Password field
	 *
	 * @var Array
	 */
	public $password = Array(
		'name'		 => 'Password',
		'type'		 => 'Password',
		'rules'		 => Array()
	);

} // END class TestForm extends Form
