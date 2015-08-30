<?php namespace Wasp\Entity;

use Wasp\Exceptions\Entity\AccessToInvalidKey,
    Wasp\DI\DependencyInjectionAwareTrait,
    JMS\Serializer\Annotation\ExclusionPolicy,
    Doctrine\ORM\Mapping as ORM;

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
    use DependencyInjectionAwareTrait;

    /**
     * Magic call method for interacting with the database
     *
     * @param String $method
     * @param Array $args
     * @return Mixed
     * @author Dan Cox
     */
    public function __call($method, Array $args = Array())
    {
        $db = $this->DI->get('database');
        $db->setEntity(get_called_class());

        return call_user_func_array([$db, $method], $args);
    }

    /**
     * Uses the paginator to perform a paginated query on the entity
     *
     * @param Integer $limit
     * @param Array $params
     * @param Array $order
     *
     * @return EntityCollection
     * @author Dan Cox
     */
    public function paginate($limit, $params = Array(), $order = Array())
    {
        $paginator = $this->DI->get('paginator');
        $paginator->setEntity(get_called_class());

        return $paginator->query($limit, $params, $order);
    }

    /**
     * Returns JSON version of entity
     *
     * @return String
     * @author Dan Cox
     */
    public function json()
    {
        $serializer = $this->DI->get('serializer');

        return $serializer->serialize($this, 'json');
    }

    /**
     * Converts entity values to array using the serializer
     * We use the serializer because it allows users to hide/show values
     *
     * @return Array
     * @author Dan Cox
     */
    public function toArray()
    {
        $serializer = $this->DI->get('serializer');
        $json = $serializer->serialize($this, 'json');

        return json_decode($json, true);
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
        foreach ($properties as $prop)
        {
            if (array_key_exists($prop->getName(), $data))
            {
                $this->{$prop->getName()} = $data[$prop->getName()];
            }
        }

        return $this;
    }

    /**
     * Saves the current state of the Entity
     *
     * @return void
     * @author Dan Cox
     */
    public function save()
    {
        $db = $this->DI->get('database');
        $db->save($this);
    }

    /**
     * Delete the current iteration of the entity.
     *
     * @return void
     * @author Dan Cox
     */
    public function delete()
    {
        $db = $this->DI->get('database');
        $db->remove($this);
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
        if (property_exists($this, $key))
        {
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
        if (property_exists($this, $key))
        {
            $this->$key = $value;
            return $this;
        }

        throw new AccessToInvalidKey(get_called_class(), $key);
    }

} // END class Entity
