<?php

namespace Wasp\Forms\FieldOutput;

use Wasp\Forms\FieldOutput\AbstractFieldOutput;
use Wasp\Utils\Str;

/**
 * Output class for select boxes
 *
 * @package Wasp
 * @subpackage FieldOutput
 * @author Dan Cox
 */
class SelectOutput extends AbstractFieldOutput
{

    /**
     * Returns a string representation of the select field
     *
     * @return String
     * @author Dan Cox
     */
    public function output(Array $extras = Array ())
    {
        $options = '';

        foreach ($this->field->values as $key => $value)
        {
            $options .= sprintf('<option value="%s"%s>%s</option>',
                $value, ($this->field->value == $value ? 'selected="selected"' : ''), $key);
        }

        return sprintf('<select name="%1$s" id="%1$s" %2$s>%3$s</select>',
            $this->field->id, Str::arrayToHtmlProperties($extras), $options);
    }

} // END class SelectOutput extends AbstractFieldOutput
