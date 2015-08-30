<?php namespace Wasp\DI\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Wasp\DI\ServiceMockeryLibrary;

/**
 * Compiler Pass for Mockery Objects
 *
 * @package Wasp
 * @subpackage DI\Pass
 * @author Dan Cox
 */
class MockeryPass implements CompilerPassInterface
{

    /**
     * The container 
     *
     * @var Object
     */
    protected $container;

    /**
     * Instance of the Library
     *
     * @var Object
     */
    protected $library;
    
    /**
     * Process the Container
     *
     * @return void
     * @author Dan Cox
     */
    public function process(ContainerBuilder $container)
    {
        $this->container = $container;
        
        // Grab the service definitions from the library.
        $definitions = $this->getLibraryDefinitions();

        foreach ($definitions as $service => $mockery)
        {
            if ($this->container->hasDefinition($service))
            {
                // Get the current definition
                $def = $this->transformDefinition($mockery);

                // Replace its current definition
                $this->container->setDefinition($service, $def);
            }           
        }
    }

    /**
     * Transforms the Definition into one we can use for mocks
     *
     * @return Object
     * @author Dan Cox
     */
    public function transformDefinition($mockery)
    {
        $definition = new Definition;

        // Set the class to the mockery decorator
        $definition->setClass('Wasp\DI\ServiceMockeryDecorator');
        
        // Add the mockery name as a definition;
        $definition->setArguments([$mockery]);

        // Incase it is a prototype scoped class
        $definition->setScope('container');

        return $definition;
    }

    /**
     * Grabs the current library definitions
     *
     * @return Array
     * @author Dan Cox
     */
    public function getLibraryDefinitions()
    {
        $this->library = new ServiceMockeryLibrary;
        
        return $this->library->all();   
    }

} // END class MockeryPass implements CompilerPassInterface
