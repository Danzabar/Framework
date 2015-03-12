<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
	Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Container Injection Pass for tagged services
 *
 * @package Wasp
 * @subpackage DI\Pass
 * @author Dan Cox
 */
class ContainerInjectionPass implements CompilerPassInterface
{
	/**
	 * Process the Pass
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function process(ContainerBuilder $container)
	{
		$containerInjectables = $container->findTaggedServiceIds('container.injectable');

		// These classes are tagged as having the DependencyInjectionAware Traits. 
		foreach($containerInjectables as $serviceId => $tags)
		{
			// Inject the Container
			$definition = $container->findDefinition($serviceId);

			$definition->addMethodCall('setDI', ['DI' => $container->get('service_container')]);
		}
	}

} // END class ContainerInjectionPass implements CompilerPassInterface
