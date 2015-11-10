<?php

use Wasp\Commands\App\CreateApp;
use Wasp\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use \Mockery as m;


/**
 * Test Case for the Create App command
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class CreateAppTest extends TestCase
{
    /**
     * Test firing the command
     *
     * @return void
     */
    public function test_fire_command()
    {
        $mock = m::mock('AppBuilder');
        $mock->shouldReceive('build');

        $this->DI->get('console')->add(new CreateApp($mock));

        $command = $this->DI->get('console')->find('createApp');

        $CT = new CommandTester($command);
        $CT->execute([
            'command'       => $command->getName(),
            'name'          => 'Wasp'
        ]);

        $this->assertContains('Created Wasp application', $CT->getDisplay());
    }
} // END class CreateAppTest extends TestCase
