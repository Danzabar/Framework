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
	 * Default field value
	 *
	 * @var String
	 */
	protected $default;

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
	 * Set of usable values for Checkbox, Radio and Select boxes
	 *
	 * @var Array | String
	 */
	protected $values;

	/**
	 * Input array from the form class
	 *
	 * @var Array
	 */
	protected $input;

	/**
	 * Create the relevent fields
	 *
	 * @param String $label
	 * @param String $type
	 * @param Array $rules
	 * @param String $default
	 * @param Array|String $values
	 * @param Array $input
	 * @author Dan Cox
	 */
	public function __construct(
		$label, 
		$type = 'String', 
		Array $rules = Array(),
		$default = '',
		$values = Array(),
		$input = Array()
	)
	{
		$this->label = $label;
		$this->id = Str::id($label);
		$this->type = $type;
		$this->values = $values;
		$this->default = $default;
		$this->rules = new Collection($rules);	
		$this->input = $input;

		$this->extractValue();
	}

	/**
	 * Checks for values using the Input Array and the Default settings
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extractValue()
	{
		$this->value = '';
		
		// Check if there is a default value set
		if (!is_null($this->default))
		{
			$this->value = $this->default;
		}
		
		// Check the Input Array for a set value.
		if (array_key_exists($this->id, $this->input)) 
		{
			$this->value = $this->input[$this->id];
		}
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
	 * Returns the current value of the input field
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function getValue()
	{
		return $this->value;
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

		return sprintf('<label %s>%s</label>', Str::arrayToHtmlProperties($elementExtras), $this->label);
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
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function createStringField(Array $extras)
	{
		return sprintf('<input type="text" name="%1$s" id="%1$s" value="%3$s" %2$s/>', $this->id, Str::arrayToHtmlProperties($extras), $this->value);
	}

	/**
	 * Creates a checkbox
	 *
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function createCheckbox(Array $extras)
	{
		if ($this->value == $this->values)
		{
			$extras = array_merge($extras, Array('checked' => 'checked'));
		}

		return sprintf('<input type="checkbox" name="%1$s" id="%1$s" value="%3$s" %2$s/>', $this->id, Str::arrayToHtmlProperties($extras), $this->values);
	}

	/**
	 * Creates a radio element
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function createRadio(Array $extras)
	{
		if ($this->value == $this->values)
		{
			$extras = array_merge($extras, Array('checked' => 'checked'));
		}

		return sprintf('<input type="radio" name="%1$s" id="%1$s" value="%3$s" %2$s/>', $this->id, Str::arrayToHtmlProperties($extras), $this->values);
	}

} // END class Field
