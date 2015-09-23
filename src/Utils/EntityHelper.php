<?php

namespace Wasp\Utils;

/**
 * Performs tasks for entities to help with updating and creating.
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
class EntityHelper
{
    /**
     * Updates and returns the entity object from given array
     *
     * @param Object $obj
     * @param Array $data
     * @return Object
     * @author Dan Cox
     */
    public static function updateFromArray($obj, Array $data = array())
    {
        // Get fields from the entity
        $reflection = new \ReflectionClass($obj);
        $properties = $reflection->getProperties();

        // Assign values
        foreach ($properties as $prop) {
            if (array_key_exists($prop->getName(), $data)) {
                $obj->{$prop->getName()} = $data[$prop->getName()];
            }
        }

        return $obj;
    }
} // END class EntityHelper
