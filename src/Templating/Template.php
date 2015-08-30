<?php

namespace Wasp\Templating;

use Symfony\Component\Templating\DelegatingEngine;
use Wasp\DI\DependencyInjectionAwareTrait;
use Wasp\Exceptions\Templating\DirectoryNotSet;

/**
 * Template class
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class Template
{
    use DependencyInjectionAwareTrait;

    /**
     * Instance of the Delegation Engine
     *
     * @var Symfony\Component\Templating\DelegatingEngine
     */
    protected $delegator;

    /**
     * The directories that templates are kept in
     *
     * @var Array
     */
    protected $directory = [];

    /**
     * An Array of available template engines
     *
     * @var Array
     */
    protected $engines = [];

    /**
     * Creates a delegating engine.
     *
     * @return void
     * @author Dan Cox
     */
    public function start()
    {
        if (empty($this->directory) || is_null($this->directory)) {
            throw new DirectoryNotSet;
        }

        $this->delegator = new DelegatingEngine($this->engines);
    }

    /**
     * Renders the template from its name with the appropriate engine
     *
     * @param String $template
     * @param Array $params
     * @return \Symfony\Component\HttpFoundation\Response
     * @author Dan Cox
     */
    public function make($template, $params = array())
    {
        return $this->DI->get('response')->make($this->delegator->render($template, $params));
    }

    /**
     * Returns a rendered template as a string
     *
     * @param String $template
     * @param Array $params
     * @return String
     * @author Dan Cox
     */
    public function render($template, $params = array())
    {
        return $this->delegator->render($template, $params);
    }

    /**
     * Sets the template directory
     *
     * @param String $directory
     * @return Template
     * @author Dan Cox
     */
    public function setDirectory($directory)
    {
        if (is_array($directory)) {
            foreach ($directory as $d) {
                $this->addDirectory($d);
            }

        } else {
            $this->addDirectory($directory);
        }

        return $this;
    }

    /**
     * Adds a directory to the array
     *
     * @param String $directory
     * @return void
     * @author Dan Cox
     */
    public function addDirectory($directory)
    {
        $this->directory[] = $directory;
    }

    /**
     * Clears the current values for the directory array
     *
     * @return Template
     * @author Dan Cox
     */
    public function clearDirectory()
    {
        $this->directory = array();
        return $this;
    }

    /**
     * Returns the template directory
     *
     * @return String
     * @author Dan Cox
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Returns the directory array as a string
     *
     * @return String
     * @author Dan Cox
     */
    public function getDirectoryString()
    {
        return join($this->directory, ',');
    }

    /**
     * Adds an engine to the array
     *
     * @return Template
     * @author Dan Cox
     */
    public function addEngine($engine)
    {
        $this->engines[] = $engine;
        return $this;
    }
} // END class Template
