<?php namespace Wasp\Exceptions\Modules;

/**
 * Exception class for missing module cache settings
 *
 * @package Wasp
 * @subpackage Exceptions\Modules
 * @author Dan Cox
 */
class MissingCacheSettings extends \Exception
{
	/**
	 * The name of the setting
	 *
	 * @var String
	 */
	protected $setting;

	/**
	 * Fire exception
	 *
	 * @param String $setting
	 * @param Integer $code
	 * @param \Exception $previous
	 * @author Dan Cox
	 */
	public function __construct($setting, $code = 0, \Exception $previous = NULL)
	{
		$this->setting = $setting;

		parent::__construct("Missing setting $setting for the Module Cache", $code, $previous);
	}

	/**
	 * Returns the missing setting name
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getSetting()
	{	
		return $this->setting;
	}

} // END class MissingCacheSettings extends \Exception
