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
	public $label;

	/**
	 * Field identifier
	 *
	 * @var String
	 */
	public $id;

	/**
	 * Raw value of the field
	 *
	 * @var Mixed
	 */
	public $value;

	/**
	 * Default field value
	 *
	 * @var String
	 */
	public $default;

	/**
	 * An instance of the abstract output class
	 *
	 * @var \Wasp\Forms\FieldOutput\AbstractOutputField
	 */
	protected $output;

	/**
	 * A Collection of rules associated with this field
	 *
	 * @var Wasp\Utils\Collection
	 */
	public $rules;

	/**
	 * Set of usable values for Checkbox, Radio and Select boxes
	 *
	 * @var Array | String
	 */
	public $values;

	/**
	 * A collection of validation errors associated with this error
	 *
	 * @var Wasp\Utils\Collection
	 */
	protected $errors;

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
	 * @param \Wasp\Forms\FieldOutput\AbstractFieldOutput $output
	 * @param String $id
	 * @param Array $rules
	 * @param String $default
	 * @param Array|String $values
	 * @param Array $input
	 * @author Dan Cox
	 */
	public function __construct(
		$label, 
		$type = 'text', 
		$output = NULL,
		$id = '',
		Array $rules = Array(),
		$default = '',
		$values = Array(),
		$input = Array()
	)
	{
		$this->label = $label;
		$this->type = $type;
		$this->output = (!is_null($output) ? $output : 'Wasp\Forms\FieldOutput\InputStringOutput');
		$this->id = ($id !== '' ? $id : Str::id($label));
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
		$outputReflection = new \ReflectionClass($this->output);
		$instance = $outputReflection->newInstance();
		$instance->load($this);

		return $instance->output($elementExtras);
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
	 * Returns the ID of the field
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getID()
	{
		return $this->id;
	}

} // END class Field
