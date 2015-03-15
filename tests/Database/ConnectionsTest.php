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

	/**
	 * Test adding a connection that has an array of models
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addConnectionWithModelArray()
	{
		$this->DI->get('connections')->add('testModelsArray', [
			'models'		=> ["test/", "test-dir/"]
		]);

		$connection = $this->DI->get('connections')->find('testModelsArray');
		$this->assertEquals(["test/", "test-dir/"], $connection->models);
	}

	/**
	 * Test adding a connection that has a single model
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_addConnectionWithSingleModel()
	{
		$this->DI->get('connections')->add('testModelSingle', [
			'models'		=> 'test'
		]);

		$connection = $this->DI->get('connections')->find('testModelSingle');
		$this->assertEquals(["test"], $connection->models);
	}

	/**
	 * Test a failing validation due to an unknown type
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_connectionValidationFail()
	{
		$this->setExpectedException("Wasp\Exceptions\Database\InvalidConnectionType");

		$this->DI->get('connections')
				 ->add('test', Array(), 'Fake');
	}

	/**
	 * Test a connection fail through an unknown type
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_connectionConnectFail()
	{
		$this->setExpectedException('Wasp\Exceptions\Database\InvalidMetaDataType');

		$this->DI->get('connections')
				 ->add('test', Array(), 'Array');

		$connection = $this->DI->get('connection');
	   	$connection->connect('test', 'Fake');	
	}

	/**
	 * Test a full working connection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fullConnectionAnnotationMetaData()
	{
		$this->DI->get('connections')
				 ->add('wasp', Array(
					 'driver'		=> 'pdo_mysql',
					 'user'			=> 'user',
					 'models'		=> '',
					 'database'		=> 'wasp'
				 ));

		$connection = $this->DI->get('connection');
		$connection->connect('wasp');

		$this->assertInstanceOf('Doctrine\ORM\EntityManager', $connection->connection());
	}

	/**
	 * Test a full working YML metadata connection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fullConnectionYAMLMetaData()
	{
		$this->DI->get('connections')
				 ->add('wasp', Array(
				 	'driver'		=> 'pdo_mysql',
					'user'			=> 'user',
					'models'		=> '',
					'database'		=> 'wasp'
				 ));

		$connection = $this->DI->get('connection');
		$connection->connect('wasp', 'YAML');

		$this->assertInstanceOf('Doctrine\ORM\EntityManager', $connection->connection());
	}

	/**
	 * Test a full working XML connection
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_fullConnectionXMLMetaData()
	{
		$this->DI->get('connections')
				 ->add('wasp', Array(
				 	'driver'		=> 'pdo_mysql',
					'user'			=> 'user',
					'models'		=> '',
					'database'		=> 'wasp'
				 ));

		$connection = $this->DI->get('connection');
		$connection->connect('wasp', 'XML');

		$this->assertInstanceOf('Doctrine\ORM\EntityManager', $connection->connection());
	}

} // END class ConnectionsTest extends TestCase

