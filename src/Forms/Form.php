<?php namespace Wasp\Forms;

use Wasp\Utils\Collection,
	Wasp\DI\DI,
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
	protected $url;

	/**
	 * Route to generate url from
	 *
	 * @var String
	 */
	protected $route;

	/**
	 * Http Method
	 *
	 * @var String
	 */
	protected $method;

	/**
	 * Route parameters
	 *
	 * @var Array
	 */
	protected $route_params;

	/**
	 * Array of input variables from the Request Class.
	 *
	 * @var Array
	 */
	protected $input;

	/**
	 * Container
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Configures the form based on its properties
	 *
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->container = DI::getContainer();

		$this->setup();
		$this->configure();
	}

	/**
	 * Setup the core form details
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		if (is_null($this->url) && !is_null($this->route))
		{
			$params = (!is_null($this->route_params) ? $this->route_params : Array());
			$this->url = $this->container->get('url')->route($this->route, $params); 
		}

		// Setup the param bag
		$this->input = $this->container->get('request')->query->all();

		if (strtoupper($this->method) == 'POST') 
		{
			$this->input = $this->container->get('request')->request->all();
		}
	}

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
				$props = $this->formatPropertyArgs($field);
				$this->fields[] = new Field(
									$props['name'], 
									$props['type'], 
									$props['rules'],
									$props['default'],
									$props['values'],
									$this->input);
			}		
		}
	}

	/**
	 * Formats field values
	 *
	 * @param Array $field
	 * @return Array
	 * @author Dan Cox
	 */
	public function formatPropertyArgs(Array $field)
	{
		$expected = ['name' => '', 'type' => 'String', 'rules' => Array(), 'default' => '', 'values' => Array()];
		$props = Array();
		
		foreach ($expected as $key => $default)
		{
			if (array_key_exists($key, $field))
			{
				$props[$key] = $field[$key];
			} else 
			{
				$props[$key] = $default;	
			}
		}

		return $props;
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

	/**
	 * Calls the validation method for each fields rules
	 *
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function validate()
	{
		$passes = true;

		foreach ($this->fields as $field)
		{
			if (!$field->validate())
			{
				$passes = false;
			}
		}

		return $passes;
	}

	/**
	 * Returns an opening form element
	 *
	 * @param Array $properties
	 * @return String
	 * @author Dan Cox
	 */
	public function open(Array $properties = Array())
	{
		return sprintf('<form action="%s" method="%s" %s>', $this->url, strtoupper($this->method), Str::arrayToHtmlProperties($properties));
	}

	/**
	 * Returns a closing form element
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function close()
	{
		return '</form>';
	}

} // END class Form
