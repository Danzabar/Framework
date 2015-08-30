<?php

namespace Wasp\Forms\FieldOutput;

use Wasp\Forms\FieldOutput\AbstractFieldOutput,
use Wasp\Utils\Str;

/**
 * Output interface for textarea elements
 *
 * @package Wasp
 * @subpackage FieldOutput
 * @author Dan Cox
 */
class TextAreaOutput extends AbstractFieldOutput
{
    /**
     * Output field
     *
     * @param Array $extras
     * @param String $value
     * @return String
     * @author Dan Cox
     */
    public function output(Array $extras = Array())
    {
        return sprintf('<textarea name="%1$s" id="%1$s" %2$s>%3$s</textarea>', $this->field->id, Str::arrayToHtmlProperties($extras), $this->field->value);
    }

} // END class TextAreaOutput extends AbstractFieldOutput
