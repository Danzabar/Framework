<?php

namespace Wasp\DI\Pass;

/**
 * Compiler helper class to perform some tasks for compilers
 *
 * @package Wasp
 * @subpackage DI\Pass
 * @author Dan Cox
 */
class CompilerHelper
{
    /**
     * Instance of the container
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    /**
     * Load container
     *
     * @author Dan Cox
     */
    public function __construct(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * Creates a service definition
     *
     * @return \Symfony\Component\DependencyInjection\Definition
     * @author Dan Cox
     */
    public function createDefinition($service, $class)
    {
        return $this->container->register($service, $class);
    }

    /**
     * Update some details from a definition
     *
     * @param String $service
     * @param String $class
     * @return \Symfony\Component\DependencyInjection\Definition
     * @author Dan Cox
     */
    public function updateDefinitionClass($service, $class)
    {
        $definition = $this->container->getDefinition($service);
        $definition->setClass($class);

        $this->container->setDefinition($service, $definition);

        return $definition;
    }

    /**
     * Update the arguments of a definition
     *
     * @param String $service
     * @param Array $arguments
     * @return \Symfony\Component\DependencyInjection\Definition
     * @author Dan Cox
     */
    public function updateDefinitionArguments($service, Array $arguments)
    {
        $definition = $this->container->getDefinition($service);
        $definition->setArguments($arguments);

        $this->container->setDefinition($service, $definition);

        return $definition;
    }

} // END class CompilerHelper
