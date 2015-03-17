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
