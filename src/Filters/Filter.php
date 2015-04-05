<?php namespace Wasp\Filters;

use Wasp\DI\DependencyInjectionAwareTrait,
	Wasp\Exceptions\Filters\InvalidFilterService;

/**
 * Filter class executes filter event classes
 *
 * @package Wasp
 * @subpackage Filters
 * @author Dan Cox
 */
class Filter
{
	use DependencyInjectionAwareTrait;

	/**
	 * The service instance
	 *
	 * @var Object
	 */
	protected $filter;

	/**
	 * An array of parameters
	 *
	 * @var Array
	 */
	protected $params;

	/**
	 * Prepares the filter parameters
	 *
	 * @return Filter
	 * @author Dan Cox
	 */
	public function prepare()
	{
		$this->params[] = $this->DI->get('router')->currentRoute();
		$this->params[] = $this->DI->get('request')->getRequest();

		return $this;
	}
	
	/**
	 * Gets the service from the DI
	 *
	 * @param String $service
	 * @param String $method
	 *
	 * @return void
	 * @throws InvalidFilterService
	 * @author Dan Cox
	 */
	public function fire($service, $method)
	{
		if ($this->DI->has($service))
		{
			$this->filter = $this->DI->get($service);
			
			return call_user_func_array([$this->filter, $method], $this->params);
		}	
		
		throw new InvalidFilterService($service);
	}

} // END class Filter
