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
		'default'	 => 'Dan');

	/**
	 * Password field
	 *
	 * @var Array
	 */
	public $password = Array(
		'name'		 => 'Password',
		'type'		 => 'Password');

	/**
	 * Remember me checkbox
	 *
	 * @var Array
	 */
	public $remember = Array(
		'name' => 'Remember me', 
		'type' => 'checkbox');
	
	/**
	 * Setup form
	 *
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->route = 'form.test';
		$this->method = 'post';

		parent::__construct();
	}

} // END class TestForm extends Form
