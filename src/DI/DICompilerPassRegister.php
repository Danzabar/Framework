<?php namespace Wasp\DI;

/**
 * Registers Compiler pass classes that are triggered in the environment
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class DICompilerPassRegister
{
		
	/**
	 * An Array of passes
	 *
	 * @var Array
	 */
	protected static $passes = Array();

	/**
	 * Returns the passes array
	 *
	 * @return Array
	 * @author Dan Cox
	 */
	public static function getPasses()
	{
		return static::$passes;
	}

	/**
	 * Adds a class to the array
	 *
	 * @param String|Array $class - Qualified class name for the pass
	 * @return void
	 * @author Dan Cox
	 */
	public static function add($class)
	{
		if (!is_array($class))
		{
			$class = Array($class);
		}	

		static::$passes = array_merge(static::$passes, $class);
	}

	/**
	 * Removes a single class from the register
	 *
	 * @param String $class
	 * @return void
	 * @author Dan Cox
	 */
	public static function remove($class)
	{
		if ($key = array_search($class, static::$passes) !== false)
		{	
			unset(static::$passes[$key]);
		}
	}

	/**
	 * Clears the list of registered classes
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public static function clear()
	{
		static::$passes = Array();
	}

} // END class DICompilerPassRegister
