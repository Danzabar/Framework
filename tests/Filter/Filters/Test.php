<?php namespace Wasp\Test\Filter\Filters;

use Wasp\Filter\FilterInterface,
	Wasp\DI\DependencyInjectionAwareTrait;

/**
 * Test filter
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class Test implements FilterInterface
{
	use DependencyInjectionAwareTrait;

	/**
	 * Filter
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function filter($event)
	{
		$response = $this->DI->get('response')->redirect('/test');

		$event->setResponse($response);
		$event->stopPropagation();

		return $event;
	}

} // END class Test implements FilterInterface

