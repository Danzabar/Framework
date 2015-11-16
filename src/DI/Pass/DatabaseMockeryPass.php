<?php

namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Wasp\DI\Pass\CompilerHelper;
use Wasp\Database\DatabaseMockery;

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
     */
    public function process(ContainerBuilder $container)
    {
        $helper = new CompilerHelper($container);
        $helper->updateDefinitionClass('database', 'Wasp\Database\DatabaseMockery');
        $helper->updateDefinitionArguments('database', [new Reference('service_container')]);
    }
} // END class DatabaseMockeryPass implements CompilerPassInterface
