<?php

namespace Wasp\Commands\App;

use Wasp\Commands\BaseCommand;
use Wasp\Application\AppBuilder;

/**
 * Command to create an application structure
 *
 * @package Wasp
 * @subpackage Commands\App
 * @author Dan Cox
 */
class CreateApp extends BaseCommand
{
    /**
     * Command Name
     *
     * @var String
     */
    protected $name = 'createApp';

    /**
     * The description
     *
     * @var String
     */
    protected $description = 'Creates an application structure';

    /**
     * Instance of the App Builder class
     *
     * @var AppBuilder
     */
    protected $builder;

    /**
     * Set up class dependencies
     *
     * @param AppBuilder $appBuilder
     */
    public function __construct($appBuilder = null)
    {
        $this->builder = (!is_null($appBuilder) ? $appBuilder : new AppBuilder(getCWD()));

        parent::__construct();
    }

    /**
     * Set up command
     *
     * @return void
     */
    public function setup()
    {
        $this
            ->argument('name', 'The name of the application', 'required')
            ->argument('namespace', 'A namespace for the application', 'optional');
    }

    /**
     * Fire command
     *
     * @return void
     */
    public function fire()
    {
        $project = $this->input->getArgument('name');
        $namespace = $this->input->getArgument('namespace');

        $this->builder->build($project, $namespace);

        $this->output->writeln("Created $project application, Don't forget to add the namespace to the composer file!");
    }
} // END class CreateApp extends BaseCommand
