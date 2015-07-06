<?php

use Wasp\Test\TestCase,
	Wasp\Test\Entity\Entities\Contact;

/**
 * Test case for the Paginator Database add-on
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class PaginatorTest extends TestCase
{
	
	/**
	 * An array of passes used by this test
	 *
	 * @var Array
	 */
	protected $passes = [
		'Wasp\DI\Pass\DatabaseMockeryPass'
	];

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$this->DI->get('database')->create(ENTITIES);

		// Inserting 50 test contacts
		for ($i = 0; $i < 51; $i ++)
		{
			$ent = new Contact;
			$ent->name = 'Test ' . $i;
			$ent->message = 'TestMessage_' . $i;
			$ent->save();
		}
	}

	/**
	 * Test basic pagination usage
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_paginatedResults()
	{
		$this->DI->get('request')->make('/test', 'GET', []);

		$records = Contact::paginate(10);
	
		$record = $records[0];

		$this->assertEquals(10, count($records));
		$this->assertEquals('Test 0', $record->name);
		$this->assertInstanceOf('Wasp\Entity\PaginatedEntityCollection', $records);

		$this->assertEquals(51, $records->getTotal());
		$this->assertEquals(10, $records->getPageSize());
		$this->assertEquals(0, $records->getPageNo());
		$this->assertEquals(6, $records->getTotalPages());
	}

	/**
	 * Test its ability to understand pages
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_offsetCalculation()
	{
		$this->DI->get('request')->make('/test', 'GET', ['page' => 2]);

		$records = Contact::paginate(10);

		$record = $records[0];

		$this->assertEquals('Test 20', $record->name);
	}

	/**
	 * Test pagination with an entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_paginationWithoutEntity()
	{
		$this->DI->get('request')->make('/test', 'GET', ['page' => 1]);
		
		$paginator = $this->DI->get('paginator');
		$records = $paginator->setEntity('Wasp\Test\Entity\Entities\Contact')
					  		 ->query(10);

		$this->assertEquals(10, count($records));
	}

} // END class PaginatorTest extends TestCase
