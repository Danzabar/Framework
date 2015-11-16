<?php

namespace Wasp\Entity;

use Wasp\Utils\Collection;
use Wasp\DI\DI;

/**
 * Collection class for entities
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class EntityCollection extends Collection
{
    /**
     * The service container
     *
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $DI;

    /**
     * Set up class base
     */
    public function __construct(Array $collectable = array(), $di = null)
    {
        parent::__construct($collectable);

        $this->DI = $di == null ? DI::getContainer() : $di;
    }

    /**
     * Converts the collection class into a json string
     *
     * @return String
     */
    public function json()
    {
        $serializer = $this->DI->get('serializer');

        return $serializer->serialize($this->collectable, 'json');
    }

    /**
     * Converts entities to array using the serializer
     *
     * @return Array
     */
    public function toArray()
    {
        $json = $this->json();

        return json_decode($json, true);
    }

    /**
     * Performs the Delete function on the whole collection of entities.
     *
     * @return void
     */
    public function delete()
    {
        foreach ($this->collectable as $entity) {
            $this->DI->get('database')->remove($entity);
        }
    }

    /**
     * Performs the Save function on the whole collection of entities
     *
     * @return void
     */
    public function save()
    {
        foreach ($this->collectable as $entity) {
            $this->DI->get('database')->save($entity);
        }
    }
} // END class EntityCollection extends Collection
