<?php namespace Wasp\Exceptions\Entity;

/**
 * Exception class for Invalid Trigger events
 *
 * @package Wasp
 * @subpackage Exception
 * @author Dan Cox
 */
class InvalidTriggerEvent extends \Exception
{

	/**
	 * The invalid event
	 *
	 * @var String
	 */
	protected $event;

	/**
	 * An array of usable events
	 *
	 * @var Array
	 */
	protected $allowedEvents;

	/**
	 * Fire exception
	 *
	 * @param String $event
	 * @param Array $allowed
	 * @param Integer $code
	 * @param \Exception $previous
	 * @author Dan Cox
	 */
	public function __construct($event, $allowed, $code = 0, \Exception $previous = NULL)
	{
		$this->event = $event;
		$this->allowedEvents = $allowed;

		parent::__construct(sprintf("Invalid entity trigger event given %s, must be one of the following: %s", $this->event, join($this->allowedEvents, ',')), $code, $previous);
	}

	/**
	 * Returns the invalid event
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * Returns the Array of allowed events
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public function getAllowedEvents()
	{
		return $this->allowedEvents;
	}

} // END class InvalidTriggerEvent extends \Exception
