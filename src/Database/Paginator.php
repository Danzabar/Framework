<?php namespace Wasp\Database;

/**
 * Creates paginated result set
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Paginator
{
	
	/**
	 * Instance of the database class
	 *
	 * @var \Wasp\Database\Database
	 */
	protected $database;

	/**
	 * A request instance
	 *
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * The entity being uses
	 *
	 * @var \Wasp\Entity\Entity
	 */
	protected $entity;

	/**
	 * Current page number as an int
	 *
	 * @var Integer
	 */
	protected $pageNo;


	/**
	 * Set up class dependencies
	 *
	 * @param \Wasp\Database\Database $database
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @author Dan Cox
	 */
	public function __construct($database, $request)
	{
		$this->database = $database;
		$this->request = $request;

	}

	/**
	 * Uses the request class to extract page number
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extractPageDetails()
	{
		$paramBag = $this->request->query;
		
		if ($paramBag->has('page'))
		{
			$this->pageNo = $paramBag->get('page');

			return;
		}

		$this->pageNo = 0;
	}

	/**
	 * Creates the pagination query
	 *
	 * @param integer $pageSize
	 * @param Array $clauses
	 * @param Array $order
	 *
	 * @return EntityCollection
	 * @author Dan Cox
	 */
	public function query($pageSize = 100, $clauses = Array(), $order = Array())
	{
		$this->extractPageDetails();

		$offset = ($pageSize * $this->pageNo);
	
		$records = $this->database->setEntity($this->entity)
								  ->get($clauses, $order, $pageSize, $offset);

		return $records;		
	}

	/**
	 * Sets the entity
	 *
	 * @return Paginator
	 * @author Dan Cox
	 */
	public function setEntity($entity)
	{
		$this->entity = $entity;

		return $this;
	}
		
} // END class Paginator
