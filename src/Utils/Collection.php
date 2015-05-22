<?php namespace Wasp\Utils;

/**
 * Collection trait, Adds useful array sorting and iterating functionality
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */	
Class Collection Implements \Iterator, \Countable, \ArrayAccess
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
	 * @author Dan Cox
	 */
	public function __construct(Array $collectable = Array())
	{
		$this->collectable = $collectable;
	}

	/**
	 * Adds a value
	 *
	 * @param String $key
	 * @param Mixed $value
	 * @return Collection
	 * @author Dan Cox
	 */
	public function add($key, $value)
	{
		$this->collectable[$key] = $value;
		return $this;
	}

	/**
	 * Returns the whole collection
	 *
	 * @return Array
	 * @author Dan Cox
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
	 * @author Dan Cox
	 */
	public function get($key)
	{
		if ($this->exists($key))
		{
			return $this->collectable[$key];
		}

		return NULL;
	}

	/**
	 * Checks if the key exists in the collection
	 * @alias
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function has($key)
	{
		return $this->exists($key);
	}

	/**
	 * Returns json version of collectable array
	 *
	 * @return String
	 * @author Dan Cox
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
	 * @author Dan Cox
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
	 * @author Dan Cox
	 */
	public function exists($key)
	{
		return array_key_exists($key, $this->collectable);
	}

	/**
	 * Rewind function as required by Iterable implementation 
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function rewind()
	{
		reset($this->collectable);
	}

	/**
	 * Current function as required by Iterable implementation
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function current()
	{
		return current($this->collectable);
	}

	/**
	 * Key function as required by Iterablt implementation
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function key()
	{
		return key($this->collectable);
	}

	/**
	 * Next function as required by Iterable implementation
	 *
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function next()
	{
		return next($this->collectable);
	}

	/**
	 * Valid function as required by Iterable implementation
	 *
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function valid()
	{
		return (!is_null(key($this->collectable)) && key($this->collectable) !== false);
	}

	/**
	 * Count function as required by Countable implementation
	 *
	 * @return Integer
	 * @author Dan Cox
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
	 * @author Dan Cox
	 */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
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
	 * @author Dan Cox
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
	 * @author Dan Cox
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
	 * @author Dan Cox
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);		
	}
}
