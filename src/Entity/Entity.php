<?php namespace Wasp\Entity;

use Wasp\DI\StaticContainerAwareTrait,
	Wasp\Exceptions\Entity\AccessToInvalidKey,
	Wasp\Exceptions\Entity\InvalidTriggerEvent,
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
	 * An array of available events
	 *
	 * @var Array
	 */
	protected $events = [
		'PrePersist',
		'PostPersist',
		'PreUpdate',
		'PostUpdate',
		'PreRemove',
		'PostRemove',
	];

	/**
	 * An Array of methods to trigger on certain actions
	 *
	 * @var Array
	 */
	protected $triggerMethods = Array();

	/**
	 * Checks the given event exists in our pre-defined array of events
	 *
	 * @param $event
	 * @return void
	 * @throws \Wasp\Exceptions\Entity\InvalidTriggerMethod
	 * @author Dan Cox
	 */
	public function validEvent($event)
	{
		if (!in_array($event, $this->events))
		{
			throw new InvalidTriggerEvent($event, $this->events);
		}
	}

	/**
	 * Registers a trigger for an event
	 *
	 * @param String $event - The name of the event, eg PrePersist
	 * @param String $method - The string name of the method to call
	 * @return void
	 * @author Dan Cox
	 */
	public function addTrigger($event, $method)
	{
		$this->validEvent($event);

		$this->triggerMethods[$event][] = $method;
	}

	/**
	 * Fires the trigger from the corresponding event
	 *
	 * @param String $event
	 * @return void
	 * @author Dan Cox
	 */
	public function fireTrigger($event)
	{
		$this->validEvent($event);

		$methods = (isset($this->triggerMethods[$event]) ? $this->triggerMethods[$event] : Array());
		
		foreach ($methods as $method)
		{
			call_user_func([$this, $method]);
		}	
	}

	/**
	 * Triggers the pre persist methods
	 *
	 * @ORM\PrePersist
	 * @return void
	 * @author Dan Cox
	 */
	public function performPrePersistMethods()
	{
		$this->fireTrigger('PrePersist');
	}

	/**
	 * Triggers the post persist methods
	 *
	 * @ORM\PostPersist
	 * @return void
	 * @author Dan Cox
	 */
	public function performPostPersistMethods()
	{
		$this->fireTrigger('PostPersist');
	}

	/**
	 * Triggers the pre update methods
	 *
	 * @ORM\PreUpdate
	 * @return void
	 * @author Dan Cox
	 */
	public function performPreUpdateMethods()
	{
		$this->fireTrigger('PreUpdate');
	}

	/**
	 * Triggers the post update methods
	 *
	 * @ORM\PostUpdate
	 * @return void
	 * @author Dan Cox
	 */
	public function performPostUpdateMethods()
	{
		$this->fireTrigger('PostUpdate');
	}

	/**
	 * Triggers the Pre Remove methods
	 *
	 * @ORM\PreRemove
	 * @return void
	 * @author Dan Cox
	 */
	public function performPreRemoveMethods()
	{
		$this->fireTrigger('PreRemove');
	}

	/**
	 * Triggers the Post Remove methods
	 *
	 * @ORM\PostRemove
	 * @return void
	 * @author Dan Cox
	 */
	public function performPostRemoveMethods()
	{
		$this->fireTrigger('PostRemove');
	}

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
	 * Uses the paginator to perform a paginated query on the entity
	 *
	 * @param Integer $limit
	 * @param Array $params
	 * @param Array $order
	 *
	 * @return EntityCollection
	 * @author Dan Cox
	 */
	public static function paginate($limit, $params = Array(), $order = Array())
	{
		$paginator = self::get('paginator');
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
