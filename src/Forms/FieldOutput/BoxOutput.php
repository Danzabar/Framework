<?php namespace Wasp\Forms\FieldOutput;

use Wasp\Forms\FieldOutput\AbstractFieldOutput,
	Wasp\Utils\Str;

/**
 * Outputing rules for checkboxes and radio buttons
 *
 * @package Wasp
 * @subpackage FieldOutput
 * @author Dan Cox
 */
class BoxOutput extends AbstractFieldOutput
{
	
	/**
	 * Return a string representation of a field
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function output(Array $extras = Array())
	{
		if ($this->field->value == $this->field->values)
		{
			$extras = array_merge($extras, ['checked' => 'checked']);
		}

		return sprintf('<input type="%1$s" name="%2$s" id="%2$s" value="%3$s" %4$s/>', 
			$this->field->type, $this->field->id, $this->field->value, Str::arrayToHtmlProperties($extras));
	}

} // END class BoxOutput extends AbstractFieldOutput
