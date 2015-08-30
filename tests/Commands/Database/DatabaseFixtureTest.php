<?php

use Wasp\Test\TestCase,
    Wasp\DI\ServiceMockery,
    Symfony\Component\Console\Tester\CommandTester;

/**
 * Test case for the database fixture command
 *
 * @package Wasp
 * @subpackage Tests\Commands
 * @author Dan Cox
 */
class DatabaseFixtureTest extends TestCase
{

    /**
     * Commands used by this test
     *
     * @var Array
     */
    protected $commands = [
        'Wasp\Commands\Database\DatabaseFixtures'
    ];

    /**
     * Set up test env
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        $mock = new ServiceMockery('fixtures');
        $mock->add();

        parent::setUp();
    }

    /**
     * Test importing fixtures
     *
     * @return void
     * @author Dan Cox
     */
    public function test_importingFixtures()
    {
        $fixtures = $this->DI->get('fixtures');
        $command = $this->DI->get('console')->find('database:fixtures');

        $fixtures->shouldReceive('load')->once();
        $fixtures->shouldReceive('import')->once();

        $CT = new CommandTester($command);
        $CT->execute([
            'command'       => $command->getName()
        ]);

        $this->assertContains('imported', $CT->getDisplay());
    }

    /**
     * Test running the purge method
     *
     * @return void
     * @author Dan Cox
     */
    public function test_purgeFixtures()
    {
        $fixtures = $this->DI->get('fixtures');
        $command = $this->DI->get('console')->find('database:fixtures');

        $fixtures->shouldReceive('load')->once();
        $fixtures->shouldReceive('purge')->once();

        $CT = new CommandTester($command);
        $CT->execute([
            'command'       => $command->getName(),
            '--purge'       => true
        ]);

        $this->assertContains('purged', $CT->getDisplay());
    }

    /**
     * Test setting a new directory before importing
     *
     * @return void
     * @author Dan Cox
     */
    public function test_setNewDirectory()
    {
        $fixtures = $this->DI->get('fixtures');
        $command = $this->DI->get('console')->find('database:fixtures');

        $fixtures->shouldReceive('setDirectory')->with('test')->once();
        $fixtures->shouldReceive('load')->once();
        $fixtures->shouldReceive('import')->once();

        $CT = new CommandTester($command);
        $CT->execute([
            'command'       => $command->getName(),
            'directory'     => 'test'
        ]);

        $this->assertContains('imported', $CT->getDisplay());
    }

} // END class DatabaseFixtureTest extends TestCase
