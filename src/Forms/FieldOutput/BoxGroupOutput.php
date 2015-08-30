<?php namespace Wasp\Forms\FieldOutput;

use Wasp\Forms\FieldOutput\AbstractFieldOutput,
    Wasp\Utils\Str;

/**
 * Field outputting for checkbox or radio groups
 *
 * @package Wasp
 * @subpackage FieldOutput
 * @author Dan Cox
 */
class BoxGroupOutput extends AbstractFieldOutput
{
    /**
     * Returns string representation of the field
     *
     * @return String
     * @author Dan Cox
     */
    public function output(Array $extras = Array())
    {
        $group = '';

        foreach ($this->field->values as $label => $value)
        {
            $e = $extras;

            if ($this->field->value == $value)
            {
                $e = array_merge($e, ['checked' => 'checked']);
            }

            $group .= '<label>';
            $group .= sprintf('<input type="%s" name="%s" value="%s" %s/>', $this->field->type, $this->field->id, $value, Str::arrayToHtmlProperties($e));
            $group .= sprintf('%s</label>', $label);
        }

        return $group;
    }

} // END class BoxGroupOutput extends AbstractFieldOutput
