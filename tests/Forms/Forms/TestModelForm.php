<?php namespace Wasp\Test\Forms\Forms;

use Wasp\Forms\Form,
	Wasp\Forms\Validation;

/**
 * A Test form class that binds a model
 *
 * @package Wasp
 * @subpackage Tests\Forms
 * @author Dan Cox
 */
class TestModelForm extends Form
{
	
	/**
	 * Name Mapping
	 *
	 * @var Array
	 */
	public $name = [
		'name'		=> 'You\'re name',
		'id'		=> 'name',
		'type'		=> 'text'
	];

	/**
	 * Message Mapping
	 *
	 * @var Array
	 */
	public $message = [
		'name'		=> 'Message',
		'type'		=> 'text'
	];

	/**
	 * Set up form
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct()
	{		
		$this->route = 'form.test';
		$this->method = 'post';

		$model = new \Wasp\Test\Entity\Entities\Contact();
		$model->name = 'Dan';
		$model->message = 'This is a default message';
		$model->save();		

		$this->model = $model;

		parent::__construct();
	}

	
} // END class TestModelForm extends Form

