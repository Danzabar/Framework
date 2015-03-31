<?php namespace Wasp\Test\Events\EventTypes;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event type class for testing
 *
 * @package Wasp
 * @author Dan Cox
 */
class TestEventRegisterType extends Event
{
	/**
	 * Foo
	 *
	 * @var string
	 */
	protected $foo;

	/**
	 * Bar
	 *
	 * @var string
	 */
	protected $bar;	

	/**
	 * Event construct
	 *
	 * @author Dan Cox
	 */
	public function __construct($foo, $bar)
	{
		$this->foo = $foo;
		$this->bar = $bar;
	}

	/**
	 * Returns foo
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getFoo()
	{
		return $this->foo;
	}

	/**
	 * Returns bar
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getBar()
	{	
		return $this->bar;
	}
	
} // END class TestEventRegisterType extends Event

