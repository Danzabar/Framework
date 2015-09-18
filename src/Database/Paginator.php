<?php

namespace Wasp\Database;

use Wasp\Entity\PaginatedEntityCollection;

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
     * Instance of the service container
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

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
     * The page size
     *
     * @var Integer
     */
    protected $pageSize;

    /**
     * The total number of pages
     *
     * @var Integer
     */
    protected $totalPages;

    /**
     * Total count of records
     *
     * @var Integer
     */
    protected $total;


    /**
     * Set up class dependencies
     *
     * @param \Wasp\Database\Database $database
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $service_container
     * @author Dan Cox
     */
    public function __construct($database, $request, $service_container)
    {
        $this->database = $database;
        $this->request = $request;
        $this->container = $service_container;
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

        if ($paramBag->has('page')) {
            $this->pageNo = $paramBag->get('page');

            return;
        }

        $this->pageNo = 1;
    }

    /**
     * Runs all the calculation functions
     *
     * @return void
     * @author Dan Cox
     */
    public function calculations()
    {
        // Count of the total rows
        $this->countRows();

        // What page are we on?
        $this->extractPageDetails();

        // How many pages do we have?
        $this->calculateTotalPages();
    }

    /**
     * Creates the pagination query
     *
     * @param integer $pageSize
     * @param Array $clauses
     * @param Array $order
     *
     * @return PaginatedEntityCollection
     * @author Dan Cox
     */
    public function query($pageSize = 100, $clauses = array(), $order = array())
    {
        $this->pageSize = $pageSize;
        $this->calculations();
        $offset = 0;

        if ($this->pageNo > 1) {
            $offset = ($pageSize * $this->pageNo);
        }

        $records = $this->container->get($this->entity)
                            ->get($clauses, $order, $pageSize, $offset);

        return $this->makeCollection($records);
    }

    /**
     * Counts rows on the table with the given clauses.
     *
     * @param Array $clauses
     * @return void
     * @author Dan Cox
     */
    public function countRows(Array $clauses = array())
    {
        $qb = $this->container->get($this->entity)
                       ->queryBuilder();

        $qb->select('count(u)');

        $this->total = $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Transfers records to a paginated entity collection
     *
     * @param \Wasp\Entity\EntityCollection $results
     * @return PaginatedEntityCollection
     * @author Dan Cox
     */
    public function makeCollection($results)
    {
        $collection = new PaginatedEntityCollection(
            $results->all(),
            $this->total,
            $this->totalPages,
            $this->pageNo,
            $this->pageSize
        );

        $collection->setDI($this->container);

        return $collection;
    }

    /**
     * Calculates how many pages we should have
     *
     * @return void
     * @author Dan Cox
     */
    public function calculateTotalPages()
    {
        $this->totalPages = ceil($this->total / $this->pageSize);
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
