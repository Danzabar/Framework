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
     * @author Dan Cox
     */
    public function __construct()
    {
        $this->container = DI::getContainer();
        $this->name = base64_encode(get_called_class());
        $this->errors = new Collection;

        $this->configure();

        $this->setup();
    }

    /**
     * Setup the core form details
     *
     * @return void
     * @author Dan Cox
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
     * Determines what input source to use
     *
     * @return void
     * @author Dan Cox
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

        $this->input = (strtoupper($this->method) == 'GET' ? $request->query->all() : $request->request->all());

        if (!is_null($this->model) && empty($this->input))
        {
            // Grab an array representation of the entity
            $this->input = $this->model->toArray();
        }
    }

    /**
     * Builds the url from route
     *
     * @return void
     * @author Dan Cox
     */
    public function buildURL()
    {
        if (is_null($this->url) && !is_null($this->route))
        {
            $params = (!is_null($this->route_params) ? $this->route_params : Array());
            $this->url = $this->container->get('url')->route($this->route, $params);
        }
    }

    /**
     * Creates fields based on the public properties from the called form class
     *
     * @param Array $field
     * @return void
     * @author Dan Cox
     */
    public function addField(Array $field = Array())
    {
        $props = $this->formatPropertyArgs($field);

        $this->fields[] = new Field(
                            $props['name'],
                            $props['type'],
                            $props['output'],
                            $props['id'],
                            $props['rules'],
                            $props['default'],
                            $props['values'],
                            $this->input);
    }

    /**
     * Loops through the forms properties.
     *
     * @return void
     * @author Dan Cox
     */
    public function processProperties()
    {
        foreach ($this->properties as $property)
        {
            $this->addField($property->getValue($this));
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
        $expected = ['name' => '', 'id' => '', 'output' => NULL,  'type' => 'String', 'rules' => Array(), 'default' => '', 'values' => Array()];
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

        $this->checkCSRFToken ();

        foreach ($this->fields as $field)
        {
            if (!$field->validate())
            {
                $this->errors->add($field->getID(), $field->errors());
                $passes = false;
            }
        }

        return $passes;
    }

    /**
     * Returns the form name
     *
     * @return String
     * @author Dan Cox
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
     * @author Dan Cox
     */
    public function checkCSRFToken()
    {
        $session = $this->container->get('session');

        if ($session->has('token_' . $this->name))
        {
            if (isset($this->input['token']) && $this->input['token'] == $session->get('token_'. $this->name))
            {
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
     * @author Dan Cox
     */
    public function generateCSRF()
    {
        $this->token = md5(uniqid(rand(), TRUE));

        // Add to the session
        $this->container->get('session')->set('token_' . $this->name, $this->token);
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
        $this->generateCSRF();

        $html = '';
        $html .= sprintf('<form action="%s" method="%s" %s>', $this->url, strtoupper($this->method), Str::arrayToHtmlProperties($properties));
        $html .= sprintf('<input type="hidden" name="token" value="%s" />', $this->token);

        return $html;
    }

    /**
     * Returns a collection of all errors from all fields
     *
     * @return Wasp\Utils\Collection
     * @author Dan Cox
     */
    public function getErrors()
    {
        return $this->errors;
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
