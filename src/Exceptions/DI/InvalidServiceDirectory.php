<?php namespace Wasp\Exceptions\DI;

/**
 * Exception for when the service definition directory has not been set
 *
 * @package Wasp
 * @subpackage Exceptions\DI
 * @author Dan Cox
 */
class InvalidServiceDirectory extends \Exception
{

	/**
	 * The DI Object
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Fire Exception
	 *
	 * @param \Wasp\DI\DI $DI
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct($DI, $code = 0, \Exception $previous = NULL)
	{
		$this->DI = $DI;

		parent::__construct("The service directory must be set before calling build on the Dependency Injection class.", $code, $previous);
	}

	/**
	 * Returns the DI Object
	 *
	 * @return DI
	 * @author Dan Cox
	 */
	public function getDI()
	{
		return $this->DI;
	}
	
} // END class InvalidServiceDirectory extends \Exception
