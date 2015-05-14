<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
	Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\DependencyInjection\Reference,
	Wasp\DI\Pass\CompilerHelper,
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
	 * Process the container
	 *
	 * @param \Symfony\Component\DepdencyInjection\ContainerBuilder $container
	 * @return void
	 * @author Dan Cox
	 */
	public function process(ContainerBuilder $container)
	{
		$helper = new CompilerHelper($container);
		$helper->updateDefinitionClass('database', 'Wasp\Database\DatabaseMockery');
		$helper->updateDefinitionArguments('database', [new Reference('service_container')]);
	}

} // END class DatabaseMockeryPass implements CompilerPassInterface

