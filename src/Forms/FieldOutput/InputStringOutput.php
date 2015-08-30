<?php namespace Wasp\Forms\FieldOutput;

use Wasp\Forms\FieldOutput\AbstractFieldOutput,
    Wasp\Utils\Str;

/**
 * Output interface class to output basic input fields
 *
 * @package Wasp
 * @subpackage FieldOutput
 * @author Dan Cox
 */
class InputStringOutput extends AbstractFieldOutput
{
    /**
     * Outputs string representation of the input field
     *
     * @param Array $extras
     * @param String $value
     * @return String
     * @author Dan Cox
     */
    public function output(Array $extras = Array())
    {
        return sprintf('<input type="%1$s" name="%2$s" id="%2$s" value="%3$s" %4$s/>',
            $this->field->type, $this->field->id, $this->field->value, Str::arrayToHtmlProperties($extras));
    }

} // END class InputStringOutput extends AbstractFieldOutput
