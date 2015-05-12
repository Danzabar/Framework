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
		$this->errors = new Collection();

		$this->extractValue();
	}

	/**
	 * Validates fields through its rules collection
	 *
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function validate()
	{
		$passes = true;

		foreach ($this->rules as $rule)
		{
			$rule->setValue($this->value);

			if (!$rule->validate())
			{
				$this->errors[] = $rule->getMessage();
				$passes = false;
			}			
		}

		return $passes;
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
	 * Returns the collection of errors
	 *
	 * @return \Wasp\Utils\Collection
	 * @author Dan Cox
	 */
	public function errors()
	{
		return $this->errors;
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
			'String'			=> 'createStringField',
			'Checkbox'			=> 'createBox',
			'Radio'				=> 'createBox',
			'TextArea'			=> 'createTextArea',
			'Select'			=> 'createSelectBox',
			'CheckboxGroup'		=> 'createBoxGroup',
			'RadioGroup'		=> 'createBoxGroup'
		);
	}

	/**
	 * Creates a select box element
	 *
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function createSelectBox(Array $extras)
	{
		$options = '';
		
		foreach ($this->values as $key => $value)
		{
			$options .= sprintf('<option value="%s"%s>%s</option>', $value, ($this->value == $value ? ' selected="selected"' : ''), $key);
		}		

		return sprintf('<select name="%1$s" id="%1$s" %2$s>%3$s</select>', $this->id, Str::arrayToHtmlProperties($extras), $options);
	}

	/**
	 * Returns an input field of various types
	 *
	 * @param String $type
	 * @param Array $extras
	 * @param Mixed $value
	 * @return String
	 * @author Dan Cox
	 */
	public function inputString($type, Array $extras, $value)
	{
		return sprintf('<input type="%1$s" name="%2$s" id="%2$s" value="%3$s" %4$s/>', $type, $this->id, $value, Str::arrayToHtmlProperties($extras));
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
		return $this->inputString('text', $extras, $this->value);
	}

	/**
	 * Creates a text area field
	 *
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function createTextArea(Array $extras)
	{
		return sprintf('<textarea name="%1$s" id="%1$s" %2$s>%3$s</textarea>', $this->id, Str::arrayToHtmlProperties($extras), $this->value);
	}

	/**
	 * Creates a group of checkboxes or radio buttons
	 *
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function createBoxGroup(Array $extras)
	{
		$type = ($this->type == 'CheckboxGroup' ? 'checkbox' : 'radio');
		$group = '';

		foreach ($this->values as $label => $value)
		{
			$fieldExtras = $extras;

			if ($this->value == $value)
			{
				$fieldExtras = array_merge($fieldExtras, Array('checked' => 'checked'));
			}

			$group .= '<label>';
			$group .= sprintf('<input type="%s" name="%s" value="%s" %s/>', $type, $this->id, $value, Str::arrayToHtmlProperties($fieldExtras));
			$group .= sprintf('%s</label>', $label);
		}

		return $group;
	}

	/**
	 * Creates check boxes or Radio boxes
	 *
	 * @param Array $extras
	 * @return String
	 * @author Dan Cox
	 */
	public function createBox(Array $extras)
	{
		$type = ($this->type == 'Checkbox' ? 'checkbox' : 'radio');

		if ($this->value == $this->values)
		{
			$extras = array_merge($extras, Array('checked' => 'checked'));	
		}
		
		return $this->inputString($type, $extras, $this->values);
	}

} // END class Field
