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
		$this->DI->get('connections')->add('test', ['test']);

		$this->assertEquals(['test'], $this->DI->get('connections')->find('test'));
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

