<?php

namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wasp\DI\Pass\CompilerHelper;

/**
 * A compiler pass to inject DI into registered models
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class EntityInjectionPass implements CompilerPassInterface
{
    /**
     * Process container
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     * @author Dan Cox
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('entity')) {
            return;
        }

        $entities = $container->findTaggedServiceIds('entity');

        foreach ($entities as $id => $tags) {
            $def = $container->findDefinition($id);
            $def->addMethodCall(['setDI', new Reference("service_container")]);
        }
    }
} // END class EntityInjectionPass implements CompilerPassInterface
