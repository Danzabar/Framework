<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
	Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\DependencyInjection\Reference,
	Wasp\Database\DatabaseMockery;

/**
 * Compiler pass for database mocking
 *
 * @package Wasp
 * @subpackage DI\Pass
 * @author Dan Cox
 */
class DatabaseMockeryPass implements CompilerPassInterface
{
	
	/**
	 * The container
	 *
	 * @var \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	protected $container;

	/**
	 * Process the container
	 *
	 * @param \Symfony\Component\DepdencyInjection\ContainerBuilder $container
	 * @return void
	 * @author Dan Cox
	 */
	public function process(ContainerBuilder $container)
	{
		$this->container = $container;

		if ($this->container->hasDefinition('database'))
		{
			$definition = $this->container->getDefinition('database');  
			
			$definition->setClass('Wasp\Database\DatabaseMockery');
			$definition->setArguments([new Reference('service_container')]);
			$this->container->setDefinition('database', $definition);
		}
	}

} // END class DatabaseMockeryPass implements CompilerPassInterface

