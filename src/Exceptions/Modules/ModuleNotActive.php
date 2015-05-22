<?php namespace Wasp\Exceptions\Modules;

/**
 * Exception class for when a module is not active
 *
 * @package Wasp
 * @subpackage Exceptions\Modules
 * @author Dan Cox
 */
class ModuleNotActive extends \Exception
{

	/**
	 * Module name
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
	public function __construct($module, $code = 0, \Exception $previous = Null)
	{
		$this->module = $module;

		parent::__construct("The module $module is not active", $code, $previous);
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

} // END class ModuleNotActive extends \Exception
