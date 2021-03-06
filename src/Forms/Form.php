<?php

namespace Wasp\Forms;

use Wasp\Utils\Collection;
use Wasp\DI\DI;
use Wasp\Utils\Str;
use Wasp\Exceptions\Forms\InvalidCSRFToken;
use Wasp\DI\DependencyInjectionAwareTrait;
use Wasp\Forms\Field;

/**
 * Form class used to build forms as classes and handle validation and submissions
 *
 * @package Wasp
 * @subpackage Forms
 * @author Dan Cox
 */
class Form
{
    use DependencyInjectionAwareTrait;

    /**
     * A Collection fields
     *
     * @var Wasp\Utils\Collection
     */
    private $fields;

    /**
     * Unique name given to form
     *
     * @var String
     */
    private $name;

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
     * A collection of validation errors assigned to fields
     *
     * @var Wasp\Utils\Collection
     */
    protected $errors;

    /**
     * The CSRF token
     *
     * @var String
     */
    protected $token;

    /**
     * Route to generate url from
     *
     * @var String
     */
    protected $route;

    /**
     * A model instance to bind to the form
     *
     * @var \Wasp\Entity\Entity
     */
    protected $model;

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
    protected $container;

    /**
     * Configures the form based on its properties
     *
     */
    public function __construct()
    {
        $this->container = DI::getContainer();
        $this->name = base64_encode(get_called_class());
        $this->errors = new Collection;

        // Configure and set up form
        $this->configure();
        $this->setup();
        $this->reinstateErrors();
    }

    /**
     * Setup the core form details
     *
     * @return void
     */
    public function setup()
    {
        // Build the URL from the given route name
        $this->buildURL();

        // Create the nessecary input param bag;
        $this->determineInput();

        $this->fields = new Collection();
        $this->reflection = new \ReflectionClass(get_called_class());
        $this->properties = $this->reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $this->processProperties();

        return $this;
    }

    /**
     * Saves the data against the given entity
     *
     * @return Boolean
     */
    public function save()
    {
        if (!is_null($this->model)) {
            $this->model->updateFromArray($this->input);
            $this->model->save();
            return true;
        }

        return false;
    }

    /**
     * Determines what input source to use
     *
     * @return void
     */
    public function determineInput()
    {
        /**
         * The order of importance of input
         *  - request data
         *  - model data
         *  - default values (these get added in the field class if no value is given)
         */
        $request = $this->container->get('request');

        $this->input = ($this->method == 'GET' ? $request->query->all() : $request->request->all());

        if (!is_null($this->model) && empty($this->input)) {
        // Grab an array representation of the entity
            $this->input = $this->model->toArray();
        }
    }

    /**
     * Builds the url from route
     *
     * @return void
     */
    public function buildURL()
    {
        if (is_null($this->url) && !is_null($this->route)) {
            $params = (!is_null($this->route_params) ? $this->route_params : array());
            $this->url = $this->container->get('url')->route($this->route, $params);
        }
    }

    /**
     * Creates fields based on the public properties from the called form class
     *
     * @param String $label - the key of this field.
     * @param Array $field
     * @return void
     */
    public function addField($label, Array $field = array())
    {
        $props = $this->formatPropertyArgs($field);

        $this->fields[$label] = new Field(
            $props['name'],
            $props['type'],
            $props['output'],
            $props['id'],
            $props['rules'],
            $props['default'],
            $props['values'],
            $this->input
        );
    }

    /**
     * Loops through the forms properties.
     *
     * @return void
     */
    public function processProperties()
    {
        foreach ($this->properties as $property) {
            $this->addField($property->getName(), $property->getValue($this));
        }
    }

    /**
     * Formats field values
     *
     * @param Array $field
     * @return Array
     */
    public function formatPropertyArgs(Array $field)
    {
        $expected = [
            'name' => '',
            'id' => '',
            'output' => null,
            'type' => 'String',
            'rules' => array(),
            'default' => '',
            'values' => array()
        ];

        $props = array();

        foreach ($expected as $key => $default) {
            if (array_key_exists($key, $field)) {
                $props[$key] = $field[$key];
            } else {
                $props[$key] = $default;
            }
        }

        return $props;
    }

    /**
     * Returns the fields associated with this form
     *
     * @return Wasp\Utils\Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Returns a specific field
     *
     * @param String $label
     * @return Field
     */
    public function getField($label)
    {
        return $this->fields->get($label);
    }

    /**
     * Returns the value from a specific field
     *
     * @param String $label
     * @return Mixed
     */
    public function getValue($label)
    {
        $field = $this->fields->get($label);

        return $field->getValue();
    }

    /**
     * Calls the validation method for each fields rules
     *
     * @return Boolean
     */
    public function validate()
    {
        $passes = true;

        $this->checkCSRFToken();

        foreach ($this->fields as $field) {
            if (!$field->validate()) {
                $this->errors->add($field->getID(), $field->errors());
                $passes = false;
            }
        }

        $this->saveErrors();

        return $passes;
    }

    /**
     * Saves errors in session to use on subsequent requests
     *
     * @return void
     */
    public function saveErrors()
    {
        $this->container->get('session')
                        ->set("form_errors_{$this->name}", $this->errors->all());
    }

    /**
     * Adds errors from the session back into the error collection
     * and removes the session entry
     *
     * @return void
     */
    public function reinstateErrors()
    {
        $session = $this->container->get('session');

        if ($session->has("form_errors_{$this->name}")) {
            $this->errors->addAll($session->get("form_errors_{$this->name}"));
            $session->remove("form_errors_{$this->name}");
        }

        $this->reinstateFieldErrors();
    }

    /**
     * Adds errors to specific fields for clarity
     *
     * @return void
     */
    public function reinstateFieldErrors()
    {
        foreach ($this->fields as $field) {
            if ($this->errors->exists($field->id)) {
                $field->errors->addAll($this->errors->get($field->id)->all());
            }
        }
    }

    /**
     * Returns the form name
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Checks that the CSRF token set is correct
     *
     * @return Boolean
     * @throws InvalidCSRFToken
     */
    public function checkCSRFToken()
    {
        $session = $this->container->get('session');

        if ($session->has('token_' . $this->name)) {
            if (isset($this->input['token']) && $this->input['token'] == $session->get('token_'. $this->name)) {
                // Passed
                return true;
            }

            throw new InvalidCSRFToken;
        }

        return false;
    }

    /**
     * Generates the csrf token and a field to output it
     *
     * @return void
     */
    public function generateCSRF()
    {
        $this->token = md5(uniqid(rand(), true));

        // Add to the session
        $this->container->get('session')->set('token_' . $this->name, $this->token);
    }

    /**
     * Returns an opening form element
     *
     * @param Array $properties
     * @return String
     */
    public function open(Array $properties = array())
    {
        $this->generateCSRF();

        $html = '';
        $html .= sprintf(
            '<form action="%s" method="%s"%s>',
            $this->url,
            strtoupper($this->method),
            Str::arrayToHtmlProperties($properties)
        );

        $html .= sprintf(' <input type="hidden" name="token" value="%s" />', $this->token);

        return $html;
    }

    /**
     * Returns a collection of all errors from all fields
     *
     * @return Wasp\Utils\Collection
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns a closing form element
     *
     * @return String
     */
    public function close()
    {
        return '</form>';
    }
} // END class Form
