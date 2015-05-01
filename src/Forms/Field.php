<?php namespace Wasp\Forms;

use Wasp\Utils\TypeMapTrait,
	Wasp\Utils\Str,
	Wasp\Utils\Collection;

/**
 * Field class controls form fields, Acts as a base for specific field types
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class Field
{
	use TypeMapTrait;

	/**
	 * Label element
	 *
	 * @var String
	 */
	protected $label;

	/**
	 * Field identifier
	 *
	 * @var String
	 */
	protected $id;

	/**
	 * Raw value of the field
	 *
	 * @var Mixed
	 */
	protected $value;

	/**
	 * A Collection of rules associated with this field
	 *
	 * @var Wasp\Utils\Collection
	 */
	protected $rules;

	/**
	 * A collection of validation errors associated with this error
	 *
	 * @var Wasp\Utils\Collection
	 */
	protected $errors;

	/**
	 * Create the relevent fields
	 *
	 * @author Dan Cox
	 */
	public function __construct($label, $type = 'String', Array $rules = Array())
	{
		$this->label = $label;
		$this->id = Str::id($label);
		$this->type = $type;
		$this->rules = new Collection($rules);
	}

	/**
	 * Creates the required elements for the field
	 *
	 * @param Array $elementExtras - An Array of values to include in the element html
	 * @return String
	 * @author Dan Cox
	 */
	public function field(Array $elementExtras = Array())
	{
		$this->fieldTypes();

		/**
		 * The Map function, defined in the TypeMapTrait Deals with
		 * Creating the relevent field and label type.
		 */			
		return $this->map($this->type, 'Wasp\Exceptions\Forms\IncorrectFieldType', [$elementExtras]);	
	}

	/**
	 * Creates a label element
	 *
	 * @param Array $elementExtras
	 * @return String
	 * @author Dan Cox
	 */
	public function label(Array $elementExtras = Array())
	{
		$elementExtras = array_merge($elementExtras, ['for' => $this->id]);

		return sprintf('<label %s>%s</label>', $this->extrasToString($elementExtras), $this->label);
	}

	/**
	 * Converts an array to string for html elements
	 *
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function extrasToString(Array $extras)
	{
		$properties = '';

		foreach ($extras as $key => $value) {
			$properties .= sprintf(' %s="%s"', $key, $value);
		}

		return ltrim($properties, ' ');
	}

	/**
	 * Builds the array of types for type map trait
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function fieldTypes()
	{
		$this->typeMap = Array(
			'String'		=> 'createStringField',
			'Checkbox'		=> 'createCheckBox',
			'Radio'			=> 'createRadio',
			'TextArea'		=> 'createTextArea'
		);
	}

	/**
	 * Creates a text field
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function createStringField(Array $extras)
	{
		return sprintf('<input type="text" name="%1$s" id="%1$s" value="" %2$s/>', $this->id, $this->extrasToString($extras));
	}

} // END class Field
