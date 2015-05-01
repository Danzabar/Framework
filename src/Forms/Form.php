<?php namespace Wasp\Forms;

use Wasp\Utils\Collection,
	Wasp\Utils\Str,
	Wasp\Forms\Field;

/**
 * Form class used to build forms as classes and handle validation and submissions
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class Form
{
	/**
	 * A Collection fields
	 *
	 * @var Wasp\Utils\Collection
	 */
	private $fields;

	/**
	 * Reflection instance of the called form class
	 *
	 * @var ReflectionClass
	 */
	private $reflection;

	/**
	 * Reflection properties
	 *
	 * @var ReflectionProperties
	 */
	private $properties;

	/**
	 * Form action url
	 *
	 * @var String
	 */
	private $url;

	/**
	 * Configures the forms settings from its parent
	 *
	 * @return Form
	 * @author Dan Cox
	 */
	public function configure()
	{
		$this->fields = new Collection();
		$this->reflection = new \ReflectionClass(get_called_class());
		$this->properties = $this->reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

		$this->processPropertyValues();

		return $this;
	}

	/**
	 * Creates fields based on the public properties from the called form class
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function processPropertyValues()
	{
		/**
		 * Each property value should be an array. It should be structured like so:
		 * Array('name' => 'test', 'type' => 'string', 'rules' => Array());
		 */
		foreach ($this->properties as $property)
		{
			$field = $property->getValue($this);

			if (is_array($field)) {
				$this->fields[] = new Field(
					$field['name'], 
					(isset($field['type']) ? $field['type'] : 'String'), 
					(isset($field['rules']) && is_array($field['rules']) ? $field['rules'] : Array())
				);
			}		
		}
	}

	/**
	 * Returns the fields associated with this form
	 *
	 * @return Wasp\Utils\Collection
	 * @author Dan Cox
	 */
	public function fields()
	{
		return $this->fields;
	}

} // END class Form
