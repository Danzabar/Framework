<?php namespace Wasp\Commands\Database;

use Wasp\Commands\BaseCommand;

/**
 * Command for updating the schema based on models
 *
 * @package Wasp
 * @subpackage Commands\Database
 * @author Dan Cox
 */
class DatabaseSchema extends BaseCommand
{

    /**
     * Command name
     *
     * @var string
     */
    protected $name = 'database:schema';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Interacts with the schema tool to interact with the database';

    /**
     * Set up command
     *
     * @return void
     * @author Dan Cox
     */
    public function setup()
    {
        $this
            ->argument('entity', 'Allows selection of a single entity', 'optional')
            ->option('remove', 'Removes a single table if entity is set, or all tables if not');
    }

    /**
     * Fire command
     *
     * @return void
     * @author Dan Cox
     */
    public function fire()
    {
        if ($this->input->getOption('remove'))
        {
            $entity = $this->input->getArgument('entity');

            if (!is_null($entity))
            {
                $this->dropTable($entity);
                $this->output->writeln("Successfully dropped $entity table");
                return;
            }

            $this->dropTables();
            $this->output->writeln("Successfully dropped all tables");
            return;
        }

        $this->buildSchema();
        $this->output->writeln("Successfully built schema from entity classes");
    }

    /**
     * Builds the schema
     *
     * @return void
     * @author Dan Cox
     */
    public function buildSchema()
    {
        $this->DI->get('schema')->update();
    }

    /**
     * Drops all tables
     *
     * @return void
     * @author Dan Cox
     */
    public function dropTables()
    {
        $this->DI->get('schema')->dropTables();
    }

    /**
     * Drops table through specific entity class
     *
     * @return void
     * @author Dan Cox
     */
    public function dropTable($class)
    {
        $this->DI->get('schema')->dropTable($class);
    }

} // END class DatabaseSchema extends BaseCommand
