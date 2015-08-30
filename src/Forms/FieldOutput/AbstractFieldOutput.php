<?php

namespace Wasp\Forms\FieldOutput;

use Wasp\Forms\Field;

/**
 * Abstract field output class
 *
 * @package Wasp
 * @subpackage FieldOutput
 * @author Dan Cox
 */
abstract class AbstractFieldOutput
{
    /**
     * Instance of the field class
     *
     * @var Wasp\Forms\Field
     */
    protected $field;

    /**
     * Load the field
     *
     * @param Wasp\Forms\Field
     * @return void
     * @author Dan Cox
     */
    public function load(Field $field)
    {
        $this->field = $field;
    }


    /**
     * Abstract output method
     *
     * @param Array $extras
     * @return String
     * @author Dan Cox
     */
    abstract public function output(Array $extras);
} // END class AbstractFieldOutput
