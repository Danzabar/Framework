<?php namespace Wasp\Entity;

use Wasp\DI\StaticContainerAwareTrait,
	Wasp\Exceptions\Entity\AccessToInvalidKey;

/**
 * The Entity class is a base for entities(models)
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
class Entity
{
	use StaticContainerAwareTrait;

	/**
	 * Access to the database through the entity
	 *
	 * @return Wasp\Database\Database
	 * @author Dan Cox
	 */
	public static function db()
	{
		$db = self::get('database');
		$db->setEntity(get_called_class());

		return $db;
	}

	/**
	 * Saves the current state of the Entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function save()
	{
		$connection = self::get('connection');

		// Persist and flush
		$connection->connection()->persist($this);
		$connection->connection()->flush();
	}

	/**
	 * Delete the current iteration of the entity.
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function delete()
	{
		$connection = self::get('connection');

		$connection->connection()->remove($this);
		$connection->connection()->flush();
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
