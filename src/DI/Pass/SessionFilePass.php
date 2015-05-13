<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
	Symfony\Component\DependencyInjection\ContainerBuilder,
	Symfony\Component\DependencyInjection\Reference,
	Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

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
			$this->container->register('session.file', new MockFileSessionStorage);

			$def = $this->container
						->getDefinition('session')
						->setArguments('session.file', [new Reference('session.file')]);

			$this->container->setDefinition('session', $def);
		}
	}

} // END class SessionFilePass implements CompilerPassInterface
