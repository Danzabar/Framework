<?php namespace Wasp\Entity;

use Wasp\DI\DependencyInjectionAwareTrait;

/**
 * The entity loader class loads entities injecting the DI
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
class EntityLoader
{
	use DependencyInjectionAwareTrait;

	/**
	 * Returns an instatiated entity class from its name
	 *
	 * @param String $entity
	 * @return Wasp\Entity\Entity
	 * @author Dan Cox
	 */
	public function load($entity)
	{
		$reflection = new \ReflectionClass($entity);

		$instance = $reflection->newInstance();
		$instance->setDI($this->DI);

		return $instance;
	}

} // END class EntityLoader
