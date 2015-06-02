<?php namespace Wasp\Entity;

use Wasp\DI\StaticContainerAwareTrait,
	Wasp\Exceptions\Entity\AccessToInvalidKey,
	Doctrine\ORM\Mapping as ORM;

/**
 * The Entity class is a base for entities(models)
 *
 * @ORM\MappedSuperClass
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
	 * Returns JSON version of entity
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function json()
	{
		$serializer = self::get('serializer');

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
		$serializer = self::get('serializer');
		$json = $serializer->serialize($this, 'json');

		return json_decode($json, true);
	}

	/**
	 * Saves the current state of the Entity
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function save()
	{
		$db = self::get('database');
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
		$db = self::get('database');
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
