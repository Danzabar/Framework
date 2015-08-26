<?php namespace Wasp\Test\Forms\Forms;

use Wasp\Forms\Form,
	Wasp\Forms\FormInterface,
	Wasp\Forms\Validation;

/**
 * Form class
 *
 * @package Wasp
 * @subpackage Tests\Forms
 * @author Dan Cox
 */
class TestForm extends Form implements FormInterface
{
	/**
	 * Username field
	 *
	 * @var Array
	 */
	public $username = Array(
		'name'		 => 'Username',
		'type'		 => 'text',
		'default'	 => 'Dan'
	);

	/**
	 * Password field
	 *
	 * @var Array
	 */
	public $password = Array(
		'name'		 => 'Password',
		'type'		 => 'password');

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	public $checkgroup = Array(
		'name'		 => 'checkgroup',
		'output'	 => 'Wasp\Forms\FieldOutput\BoxGroupOutput',
		'type'		 => 'checkbox',
		'values'	 => ['Yes' => 'Y', 'No' => 'N']
	);

	/**
	 * Remember me checkbox
	 *
	 * @var Array
	 */
	public $remember = Array(
		'name' => 'Remember me',
		'output' => 'Wasp\Forms\FieldOutput\BoxOutput',
		'type' => 'checkbox');

	/**
	 * Setup form
	 *
	 * @author Dan Cox
	 */
	public function configure()
	{
		$this->route = 'form.test';
		$this->method = 'post';

		// Add the rules
		$this->password['rules'] = Array(new Validation\Required());
	}

} // END class TestForm extends Form
