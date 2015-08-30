<?php namespace Wasp\Entity;

use Wasp\Utils\Collection,
    Wasp\DI\DI;

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
     * Converts the collection class into a json string
     *
     * @return String
     * @author Dan Cox
     */
    public function json()
    {
        $serializer = DI::getContainer()->get('serializer');

        return $serializer->serialize($this->collectable, 'json');
    }

    /**
     * Converts entities to array using the serializer
     *
     * @return Array
     * @author Dan Cox
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
     * @author Dan Cox
     */
    public function delete()
    {
        foreach ($this->collectable as $entity)
        {
            $entity->delete();
        }
    }

    /**
     * Performs the Save function on the whole collection of entities
     *
     * @return void
     * @author Dan Cox
     */
    public function save()
    {
        foreach ($this->collectable as $entity)
        {
            $entity->save();
        }
    }

} // END class EntityCollection extends Collection
