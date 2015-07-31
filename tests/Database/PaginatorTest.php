<?php

use Wasp\Test\TestCase,
	Symfony\Component\DomCrawler\Crawler,
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
	 * Instance of the contact entity
	 *
	 * @var \Wasp\Test\Entity\Entities\Contact
	 */
	protected $contact;

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
			$ent = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Contact');
			$ent->name = 'Test ' . $i;
			$ent->message = 'TestMessage_' . $i;
			$ent->save();
		}

		$this->contact = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Contact');
	}

	/**
	 * Test that the template for pagination renders correctly where it is set
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_templateRendering()
	{
		$this->setupTemplates(TEMPLATES);
		$this->DI->get('profile')->setSettings(['database' => ['pagination_template' => 'pagination.html.twig']]);

		$this->DI->get('request')->make('/test', 'GET', []);

		$collection = $this->contact->paginate(10);

		$crawler = new Crawler ($collection->pagination());
		$next = $crawler->filterXPath('//a')->attr('href');

		$this->assertEquals('/?page=2', $next);
	}

	/**
	 * Test with no profile settings
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_noTemplateSettings()
	{
		$this->setExpectedException('Wasp\Exceptions\Entity\PaginationTemplateNotSet');

		$this->DI->get('profile')->setSettings(['database' => []]);
		$this->DI->get('request')->make('/test', 'GET', []);

		$collection = $this->contact->paginate(10);

		$pagination = $collection->pagination();
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

		$records = $this->contact->paginate(10);

		$record = $records[0];

		$this->assertEquals(10, count($records));
		$this->assertEquals('Test 0', $record->name);
		$this->assertInstanceOf('Wasp\Entity\PaginatedEntityCollection', $records);

		$this->assertEquals(51, $records->getTotal());
		$this->assertEquals(10, $records->getPageSize());
		$this->assertEquals(1, $records->getPageNo());
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

		$records = $this->contact->paginate(10);

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
