<?php namespace Wasp\Commands;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wasp\Utils\ArgumentMappingTrait;
use Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Base command class
 *
 * @package Wasp
 * @subpackage Commands
 * @author Dan Cox
 */
class BaseCommand extends SymfonyCommand
{
    use DependencyInjectionAwareTrait, ArgumentMappingTrait;

    /**
     * The command name
     *
     * @var String
     */
    protected $name;

    /**
     * The command description
     *
     * @var String
     */
    protected $description;

    /**
     * Instance of the InputInterface
     *
     * @var Object
     */
    protected $input;

    /**
     * Instance of the OutputInterface
     *
     * @var Object
     */
    protected $output;

    /**
     * Mapped class constants for Input Options
     *
     * @var Array
     */
    protected $inputOptionRequirements = [
        'array'         => InputOption::VALUE_IS_ARRAY,
        'required'      => InputOption::VALUE_REQUIRED,
        'optional'      => InputOption::VALUE_OPTIONAL,
        'none'          => InputOption::VALUE_NONE
    ];

    /**
     * undocumented class variable
     *
     * @var string
     */
    protected $inputArgumentRequirements = [
        'array'         => InputArgument::IS_ARRAY,
        'required'      => InputArgument::REQUIRED,
        'optional'      => InputArgument::OPTIONAL
    ];

    /**
     * Set up the command details
     *
     */
    public function __construct()
    {
        parent::__construct($this->name);
        $this->setDescription($this->description);
        $this->argumentInputMappings();
        $this->setup();
    }

    /**
     * Adds the constant values for input arguments and options
     *
     * @return void
     */
    public function argumentInputMappings()
    {
        $this->addArgumentMap('arguments', $this->inputArgumentRequirements);
        $this->addArgumentMap('options', $this->inputOptionRequirements);
    }

    /**
     * Adds an argument expectation to the command
     *
     * @param String $name
     * @param String $description
     * @param String $required
     * @return BaseCommand
     */
    public function argument($name, $description, $required = 'required')
    {
        $this->addArgument(
            $name,
            $this->getArgumentMapValue('arguments', $required),
            $description
        );

        return $this;
    }

    /**
     * Adds an option expectation to the command
     *
     * @param String $name
     * @param String $description
     * @param String $required
     * @param String $keyBinding
     * @return BaseCommand
     */
    public function option($name, $description, $required = 'optional', $keyBinding = null)
    {
        $this->addOption(
            $name,
            $keyBinding,
            $this->getArgumentMapValue('options', $required),
            $description
        );

        return $this;
    }

    /**
     * Execution script for the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->fire();
    }
} // END class BaseCommand extends SymfonyCommand
