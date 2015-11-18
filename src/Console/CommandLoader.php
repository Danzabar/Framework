<?php

namespace Wasp\Console;

/**
 * Command loader class loads commands into the console application in a variation of ways
 *
 * @package Wasp
 * @subpackage Console
 * @author Dan Cox
 */
class CommandLoader
{

    /**
     * Instance of the console class
     *
     * @var \Wasp\Console\ConsoleApplication
     **/
    protected $console;

    /**
     * Get dependencies
     *
     * @param \Wasp\Console\ConsoleApplication $console
     **/
    public function __construct($console)
    {
        $this->console = $console;
    }

    /**
     * Loads commands listed as an array inside a file
     *
     * @param String $file
     * @return void
     */
    public function fromFile($file)
    {
        $data = require_once $file;

        $this->fromArray($data);
    }

    /**
     * Loads commands from an array
     *
     * @param Array $commands
     * @return void
     */
    public function fromArray($commands)
    {
        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    /**
     * Adds a single command instance
     *
     * @param String $command - A Fully Qualified class
     * @return void
     */
    public function add($command)
    {
        $reflection = new \ReflectionClass($command);
        $instance = $reflection->newInstance();

        $this->console->add($instance);
    }
} // END class CommandLoader
