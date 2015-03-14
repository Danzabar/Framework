<?php

use Wasp\Test\TestCase;

/**
 * Test case for the connection classes
 *
 * @package Wasp
 * @subpackage Test\Database
 * @author Dan Cox
 */
class ConnectionsTest extends TestCase
{

	/**
	 * Test adding connections
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addConnections()
	{
		$this->DI->get('connections')->add('test', [
			'driver'		=> 'mysqli',
			'user'			=> 'user',
			'debug'			=> false
		]);

		$connection = $this->DI->get('connections')->find('test');
		$this->assertEquals('mysqli', $connection->details['driver']);
		$this->assertEquals('user', $connection->details['user']);
		$this->assertFalse($connection->debug);
		$this->assertEquals(Array(), $connection->models);
	}

	/**
	 * Test failing to get a connection back
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_connectionFail()
	{
		$this->setExpectedException("Wasp\Exceptions\Database\InvalidConnection");

		$this->DI->get('connections')->find('fake');
	}

} // END class ConnectionsTest extends TestCase

