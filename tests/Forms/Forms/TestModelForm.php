<?php namespace Wasp\Test\Forms\Forms;

use Wasp\Forms\Form,
    Wasp\DI\DI,
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
        'name'      => 'You\'re name',
        'id'        => 'name',
        'type'      => 'text'
    ];

    /**
     * Message Mapping
     *
     * @var Array
     */
    public $message = [
        'name'      => 'Message',
        'type'      => 'text'
    ];

    /**
     * Set up form
     *
     * @return void
     * @author Dan Cox
     */
    public function configure()
    {
        $this->route = 'form.test';
        $this->method = 'post';

        $model = DI::getContainer()->get('entity')->load('Wasp\Test\Entity\Entities\Contact');
        $model->name = 'Dan';
        $model->message = 'This is a default message';
        $model->save();

        $this->model = $model;
    }


} // END class TestModelForm extends Form

