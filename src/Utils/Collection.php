<?php

namespace Wasp\Utils;

/**
 * Collection trait, Adds useful array sorting and iterating functionality
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
class Collection implements \Iterator, \Countable, \ArrayAccess
{
    /**
     * The raw array
     *
     * @var Array
     */
    protected $collectable;

    /**
     * Load the collectable
     *
     * @param Array $collectable
     */
    public function __construct(Array $collectable = array())
    {
        $this->collectable = $collectable;
    }

    /**
     * Adds a value
     *
     * @param String $key
     * @param Mixed $value
     * @return Collection
     */
    public function add($key, $value)
    {
        $this->collectable[$key] = $value;
        return $this;
    }

    /**
     * Replaces all the items of the collectable array
     *
     * @return Collection
     */
    public function replaceAll(array $collectable)
    {
        $this->collectable = $collectable;
        return $this;
    }

    /**
     * Returns all array keys
     *
     * @return Array
     */
    public function keys()
    {
        return array_keys($this->collectable);
    }

    /**
     * Adds all from an array into the collection
     *
     * @param Array $collection
     * @return Collection
     */
    public function addAll(Array $collection)
    {
        foreach ($collection as $key => $value) {
            $this->add($key, $value);
        }

        return $this;
    }

    /**
     * Returns the whole collection
     *
     * @return Array
     */
    public function all()
    {
        return $this->collectable;
    }

    /**
     * Returns value associated with specified key
     *
     * @param String|Integer $key
     * @return Mixed
     */
    public function get($key)
    {
        if ($this->exists($key)) {
            return $this->collectable[$key];
        }

        return null;
    }

    /**
     * Appends an array to the current collectable array
     *
     * @param Array $values
     * @return Collection
     */
    public function append(Array $values)
    {
        $this->collectable = array_merge($this->collectable, $values);

        return $this;
    }

    /**
     * Prepends an array to the current collectable
     *
     * @param Array $values
     * @return Collection
     */
    public function prepend(Array $values)
    {
        $this->collectable = array_merge($values, $this->collectable);

        return $this;
    }

    /**
     * Returns json version of collectable array
     *
     * @return String
     */
    public function json()
    {
        return json_encode($this->collectable);
    }

    /**
     * Unsets a value from the collectable by key
     *
     * @param String|Integer $key
     * @return Collection
     */
    public function remove($key)
    {
        unset($this->collectable[$key]);
        return $this;
    }

    /**
     * Checks if an entry with specifed key exists
     *
     * @param String|Integer $key
     * @return Boolean
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->collectable);
    }

    /**
     * Rewind function as required by Iterable implementation
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->collectable);
    }

    /**
     * Current function as required by Iterable implementation
     *
     * @return Mixed
     */
    public function current()
    {
        return current($this->collectable);
    }

    /**
     * Key function as required by Iterablt implementation
     *
     * @return String
     */
    public function key()
    {
        return key($this->collectable);
    }

    /**
     * Next function as required by Iterable implementation
     *
     * @return Mixed
     */
    public function next()
    {
        return next($this->collectable);
    }

    /**
     * Valid function as required by Iterable implementation
     *
     * @return Boolean
     */
    public function valid()
    {
        return (!is_null(key($this->collectable)) && key($this->collectable) !== false);
    }

    /**
     * Count function as required by Countable implementation
     *
     * @return Integer
     */
    public function count()
    {
        return count($this->collectable);
    }

    /**
     * Offset set function, required by ArrayAccess implementation
     *
     * @param String|Integer $offset
     * @param Mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->collectable[] = $value;
        } else {
            $this->collectable[$offset] = $value;
        }
    }

    /**
     * Offset exists function, required by ArrayAccess implementation
     *
     * @param String|Integer $offset
     * @return Boolean
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * Offset unset function, required by ArrayAccess implementation
     *
     * @param String|Integer $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * Offset get function, required by ArrayAccess implementation
     *
     * @param String|Integer $offset
     * @return Mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}
