<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
	Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\DependencyInjection\Reference,
	Wasp\DI\Pass\CompilerHelper,
	Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * Redifines the session reference in the DI to include the Mock File Session Storage
 *
 * @package Wasp
 * @subpackage DI\Pass
 * @author Dan Cox
 */
class SessionFilePass implements CompilerPassInterface
{
	/**
	 * Process the compiler
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return void
	 * @author Dan Cox
	 */
	public function process(ContainerBuilder $container)
	{
		$helper = new CompilerHelper($container);
		$helper->createDefinition('session.storage', new MockFileSessionStorage);
		$helper->updateDefinitionArguments('session', [new Reference('session.storage')]);
	}

} // END class SessionFilePass implements CompilerPassInterface
