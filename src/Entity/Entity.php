<?php

namespace Wasp\Entity;

use Wasp\DI\DI;
use Wasp\Exceptions\Entity\AccessToInvalidKey;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Doctrine\ORM\Mapping as ORM;
use Wasp\Utils\EntityHelper;

/**
 * The Entity class is a base for entities(models)
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 * @ExclusionPolicy("all")
 */
class Entity
{
    /**
     * Update an entity from an array of values
     *
     * @param Array $data
     * @return this
     */
    public function updateFromArray(Array $data)
    {
        return EntityHelper::updateFromArray($this, $data);
    }

    /**
     * Returns an array representation of the entity
     *
     * @return Array
     */
    public function toArray()
    {
        return json_decode($this->toJSON(), true);
    }

    /**
     * Returns json representation of the entity
     *
     * @return String
     */
    public function toJSON()
    {
        return DI::getContainer()
                    ->get('serializer')
                    ->serialize($this, 'json');
    }

    /**
     * Saves the current state of the Entity
     *
     * @return void
     */
    public function save()
    {
        DI::getContainer()
            ->get('database')
            ->save($this);
    }

    /**
     * Delete the current iteration of the entity.
     *
     * @return void
     */
    public function delete()
    {
        DI::getContainer()
            ->get('database')
            ->remove($this);
    }

    /**
     * Magic Getter for entities
     *
     * @param Mixed $key
     * @return Mixed
     */
    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }

        throw new AccessToInvalidKey(get_called_class(), $key);
    }

    /**
     * Magic Setter for entities
     *
     * @param Mixed $key
     * @param Mixed $value
     * @return Entity
     */
    public function __set($key, $value)
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
            return $this;
        }

        throw new AccessToInvalidKey(get_called_class(), $key);
    }
} // END class Entity
