<?php

namespace Wasp\Commands\Database;

use Wasp\Commands\BaseCommand;

/**
 * Command class for importing and purging fixtures
 *
 * @package Wasp
 * @subpackage Commands\Database
 * @author Dan Cox
 */
class DatabaseFixtures extends BaseCommand
{

    /**
     * Command Name
     *
     * @var String
     */
    protected $name = 'database:fixtures';

    /**
     * Command description
     *
     * @var String
     */
    protected $description = 'Allows usage of the fixture manager to import and purge fixture sets.';

    /**
     * Set up command
     *
     * @return void
     * @author Dan Cox
     */
    public function setup()
    {
        $this
            ->argument('directory', 'Set a different directory to use', 'optional')
            ->option('append', 'Specifies whether the fixture should be appended rather than purged');
    }

    /**
     * Fire command
     *
     * @return void
     * @author Dan Cox
     */
    public function fire()
    {
        $FM = $this->DI->get('fixtures');
        $append = false;

        if (!is_null($this->input->getArgument('directory'))) {
            $FM->setDirectory($this->input->getArgument('directory'));
        }

        $FM->load();

        if ($this->input->getOption('append')) {
            $append = true;
        }

        $FM->import($append);
        $this->output->writeln("Successfully imported fixtures");
    }
} // END class DatabaseFixtures extends BaseCommand
