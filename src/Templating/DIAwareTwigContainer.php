<?php namespace Wasp\Templating;

/**
 * A decorator class that makes it easier to use the container inside twig.
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class DIAwareTwigContainer
{
	/**
	 * An instance of the symfony container
	 *
	 * @var \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	protected $container;

	/**
	 * Set up dependencies
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @author Dan Cox
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

	/**
	 * Returns an Instanced service
	 *
	 * @return Object
	 * @author Dan Cox
	 */
	public function __call($key, $params = Array())
	{	
		return $this->container->get($key);
	}


} // END class DIAwareTwigContainer
