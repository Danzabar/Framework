<?php namespace Wasp\Utils;

/**
 * Type map trait calls class functions based on types
 *
 * @package Wasp
 * @subpackage Utilities
 * @author Dan Cox
 */
Trait TypeMapTrait
{
	/**
	 * An associative array of type mappings
	 *
	 * @var Array
	 */
	protected $typeMap;

	/**
	 * Calls the mapped function type
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function map($type, $notFoundException = 'Exception')
	{
		if(array_key_exists($type, $this->typeMap))
		{
			call_user_func([$this, $this->typeMap[$type]]);
		} else
		{
			throw new $notFoundException($type, $this->typeMap);
		}
	}
}
