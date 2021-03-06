<?php

namespace Wasp\Database;

use Wasp\Exceptions\Entity\RecordNotFound;

/**
 * The database class interacts with the database through Doctrine ORM
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Database
{
    /**
     * Instance of the Connection class
     *
     * @var Object
     */
    public $connection;

    /**
     * Instance of the symfony http request
     *
     * @var Wasp\Routing\Request
     */
    protected $request;

    /**
     * Relates to an entity object, its name.
     *
     * @var String
     */
    protected $entity;

    /**
     * Set up class vars
     *
     * @param Wasp\Database\Connection $connection
     * @param Wasp\Routing\Request
     */
    public function __construct($connection, $request = null)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    /**
     * Sets the entity to be used by queries
     *
     * @param String $entity - An entities name
     * @return Database
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Find an entity entry by its identifier
     *
     * @param Mixed $identifier - An id that relates to this entities identifier
     * @return Object
     */
    public function find($identifier)
    {
        return $this->perform()
                    ->find($this->entity, $identifier);
    }

    /**
     * Finds a single record by params.
     *
     * @param Array $params - An array of parameters
     * @param Array $order - The query order
     * @return Object
     */
    public function findOneBy($params = array(), $order = array())
    {
        return $this->performOnRepository($this->entity)
                    ->findOneBy($params, $order, null, null);
    }

    /**
     * Finds a single record or throws an exception if not found
     *
     * @param Array $params
     * @param Array $order
     * @return Object
     * @throws \Wasp\Exceptions\Entity\RecordNotFound
     */
    public function findOrFail($params = array(), $order = array())
    {
        $result = $this->findOneBy($params, $order);

        if (is_null($result)) {
            throw new RecordNotFound();
        }

        return $result;
    }

    /**
     * Find multiple records from a repository
     *
     * @param Array $params - An assoc array of params
     * @param Array $order  - Query order
     * @param Integer $limit - Query limit
     * @param Integer $offset - Query offset
     * @return Array
     */
    public function get($params = array(), $order = array(), $limit = null, $offset = null)
    {
        $data = $this->performOnRepository($this->entity)->findBy($params, $order, $limit, $offset);

        return $this->data($data);
    }

    /**
     * Saves an entities state
     *
     * @param Object $entity
     * @return void
     */
    public function save($entity)
    {
        $this->perform()->persist($entity);
        $this->perform()->flush();
    }

    /**
     * Saves a collection of entities
     *
     * @param Collection|Array $collection
     * @return void
     */
    public function saveAll($collection)
    {
        if (is_a($collection, 'Wasp\Utils\Collection')) {
            $collection = $collection->all();
        }

        foreach ($collection as $row) {
            $this->save($row);
        }
    }

    /**
     * Remove an entity
     *
     * @param Object $entity
     * @return void
     */
    public function remove($entity)
    {
        $this->perform()->remove($entity);
        $this->perform()->flush();
    }

    /**
     * Removes a collection of entities
     *
     * @param Collection|Array $collection
     * @return void
     */
    public function removeAll($collection)
    {
        if (is_a($collection, 'Wasp\Utils\Collection')) {
            $collection = $collection->all();
        }

        foreach ($collection as $col) {
            $this->remove($col);
        }
    }

    /**
     * Performs a query from a string
     *
     * @param String $query
     * @return Mixed
     */
    public function raw($query, $execute = true)
    {
        $query = $this->perform()
                      ->getConnection()
                      ->prepare($query);

        if ($execute) {
            $query->execute();
        }

        return $query;
    }

    /**
     * Returns the query builder class
     *
     * @return Object
     */
    public function queryBuilder()
    {
        $builder = $this->perform()
                        ->createQueryBuilder();

        // Set the entity
        $builder->from($this->entity, 'u');

        return $builder;
    }

    /**
     * Returns a paginated entity collection
     *
     * @param Integer $limit
     * @return Wasp\Entity\PaginatedEntityCollection
     */
    public function paginate($limit = 100, $clauses = array(), $order = array())
    {
        $paginator = new \Wasp\Database\Paginator($this, $this->request);
        $paginator->setEntity($this->entity);

        return $paginator->query($limit, $clauses, $order);
    }

    /**
     * Returns the entity manager on the current connection
     *
     * @return Object
     */
    public function perform()
    {
        return $this->connection->connection();
    }

    /**
     * Returns the Repository from the entity manager
     *
     * @return Object
     */
    public function performOnRepository($entity)
    {
        return $this->perform()->getRepository($entity);
    }

    /**
     * Returns results as collection
     *
     * @param Array $results
     * @return Wasp\Entity\Collection | Object
     */
    public function data($results)
    {
        return new \Wasp\Entity\EntityCollection($results);
    }

    /**
     * Returns the current entity
     *
     * @return String
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Returns the entity Manager - Alias for Peform
     *
     * @return Object
     */
    public function entityManager()
    {
        return $this->perform();
    }
} // END class Database
