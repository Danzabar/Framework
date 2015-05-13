<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
	Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\DependencyInjection\Reference,
	Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Redefines the session reference in the DI to include the Mock Array Session storage
 *
 * @package Wasp
 * @subpackage DI\Pass
 * @author Dan Cox
 */
class SessionArrayPass implements CompilerPassInterface
{
	
	/**
	 * Instance of the container
	 *
	 * @var \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	protected $container;

	/**
	 * Process the compiler
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return void
	 * @author Dan Cox
	 */
	public function process(ContainerBuilder $container)
	{
		$this->container = $container;

		if ($this->container->hasDefinition('session'))
		{
			$this->container->register('session.storage', new MockArraySessionStorage);

			$definition = $this->container->getDefinition('session');
			$definition->setArguments([new Reference('session.storage')]);	
			$this->container->setDefinition('session', $definition);	
		}
	}

} // END class SessionArrayPass implements CompilerPassInterfacre
