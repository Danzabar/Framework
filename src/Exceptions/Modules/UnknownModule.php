<?php namespace Wasp\Exceptions\Modules;

/**
 * Exception class for unknown module interactions
 *
 * @package Wasp
 * @subpackage Exceptions\Modules
 * @author Dan Cox
 */
class UnknownModule extends \Exception
{
	
	/**
	 * Module name attempted
	 *
	 * @var String
	 */
	protected $module;

	/**
	 * Fire exception
	 *
	 * @param String $module
	 * @param Integer $code
	 * @param \Exception $previous
	 * @author Dan Cox
	 */
	public function __construct($module, $code = 0, \Exception $previous = NULL)
	{
		$this->module = $module;

		parent::__construct("The module $module could not be found, check your available module configuration", $code, $previous);
	}

	/**
	 * Returns the module name
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getModule()
	{	
		return $this->module;
	}

} // END class UnknownModule extends \Exception

