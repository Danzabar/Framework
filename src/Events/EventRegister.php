<?php namespace Wasp\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Registers event class types for the dispatcher to use
 *
 * @package Wasp
 * @subpackage Events
 * @author Dan Cox
 */
class EventRegister
{
	/**
	 * An Array of event types mapped to classes
	 *
	 * @var Array
	 */
	protected $eventTypes;

	/**
	 * Instance of the Symfony Container aware dispatcher
	 *
	 * @var \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher
	 */
	protected $dispatch;

	/**
	 * Load dependencies
	 *
	 * @param \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher
	 * @author Dan Cox
	 */
	public function __construct($dispatch)
	{
		$this->dispatch = $dispatch;
		$this->eventTypes = Array();
	}

	/**
	 * Fires an event
	 *
	 * @param String $name
	 * @param Array $params
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function fire($name, Array $params = Array())
	{
		$event = $this->initEvent($name, $params);

		return $this->dispatch->dispatch($name, $event);
	}

	/**
	 * Creates an event object from its name
	 *
	 * @param String $name
	 * @param Array $params
	 *
	 * @return Object
	 * @author Dan Cox
	 */
	public function initEvent($name, Array $params)
	{
		if (array_key_exists($name, $this->eventTypes))
		{
			$event = new \ReflectionClass($this->eventTypes[$name]);
			
			return $event->newInstanceArgs($params);	
		}
		
		// Return the generic event if theres no registered.
		return new Event;	
	}

	/**
	 * Registers an array of types
	 *
	 * @param Array $types
	 *
	 * @return EventRegister
	 * @author Dan Cox
	 */
	public function registerTypes(Array $types)
	{
		$this->eventTypes = array_merge($this->eventTypes, $types);

		return $this;
	}

	/**
	 * Registers a single event
	 *
	 * @param String $event - the event name
	 * @param String $class - qualified class name
	 *
	 * @return EventRegister
	 * @author Dan Cox
	 */
	public function registerType($event, $class)
	{	
		$this->eventTypes[$event] = $class;

		return $this;
	}

	/**
	 * Returns the array of event types
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function getEventTypes()
	{
		return $this->eventTypes;
	}

} // END class EventRegister
