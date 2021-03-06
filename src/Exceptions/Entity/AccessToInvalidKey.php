<?php

namespace Wasp\Exceptions\Entity;

/**
 * Exception class for accessing invalid keys on an entity
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class AccessToInvalidKey extends \Exception
{

    /**
     * The entity attempted
     *
     * @var String
     */
    protected $entity;

    /**
     * The key
     *
     * @var String
     */
    protected $key;

    /**
     * Fire exception
     *
     * @param string $entity
     * @return void
     */
    public function __construct($entity, $key, $code = 0, \Exception $previous = null)
    {
        $this->entity = $entity;
        $this->key = $key;

        parent::__construct("An attempt to use invalid key $key on entity $entity", $code, $previous);
    }

    /**
     * Returns the entity
     *
     * @return String
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Returns the key
     *
     * @return String
     */
    public function getKey()
    {
        return $this->key;
    }
} // END class AccessToInvalidKey extends \Exception
