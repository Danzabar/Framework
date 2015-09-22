<?php

namespace Wasp\Entity;

use Wasp\DI\DI;
use Wasp\Exceptions\Entity\AccessToInvalidKey;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Doctrine\ORM\Mapping as ORM;

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
     * Instance of the database
     *
     * @var Wasp\Database\Database
     */
    protected $database;

    /**
     * Instance of the serializer
     *
     * @var Wasp\Utils\Serializer
     */
    protected $serializer;

    /**
     * Set up base entity
     *
     * @return void
     * @author Dan Cox
     */
    public function __construct()
    {
        $di = DI::getContainer();

        $this->database = $di->get('database');
        $this->serializer = $di->get('serializer');
    }

    /**
     * Update an entity from an array of values
     *
     * @param Array $data
     * @return
     * @author Dan Cox
     */
    public function updateFromArray(Array $data)
    {
        // Get fields from the entity
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();

        // Assign values
        foreach ($properties as $prop) {
            if (array_key_exists($prop->getName(), $data)) {
                $this->{$prop->getName()} = $data[$prop->getName()];
            }
        }

        return $this;
    }

    /**
     * Returns an array representation of the entity
     *
     * @return Array
     * @author Dan Cox
     */
    public function toArray()
    {
        return json_decode($this->toJSON(), true);
    }

    /**
     * Returns json representation of the entity
     *
     * @return String
     * @author Dan Cox
     */
    public function toJSON()
    {
        return $this->serializer->serialize($this, 'json');
    }

    /**
     * Saves the current state of the Entity
     *
     * @return void
     * @author Dan Cox
     */
    public function save()
    {
        $this->database->save($this);
    }

    /**
     * Delete the current iteration of the entity.
     *
     * @return void
     * @author Dan Cox
     */
    public function delete()
    {
        $this->database->remove($this);
    }

    /**
     * Magic Getter for entities
     *
     * @param Mixed $key
     * @return Mixed
     * @author Dan Cox
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
     * @author Dan Cox
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
