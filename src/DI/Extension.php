<?php namespace Wasp\DI;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Extension\ExtensionInterface,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader,
    Symfony\Component\Config\FileLocator;


/**
 * A Base extension class for the DI
 *
 * @package Wasp
 * @subpackage DI
 * @author Dan Cox
 */
class Extension implements ExtensionInterface
{

    /**
     * An Array of configurations from the container
     *
     * @var Array
     */
    protected $configs;

    /**
     * A container builder instance
     *
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    /**
     * An instanced YAML loader
     *
     * @var string
     */
    protected $loader;

    /**
     * Directory location
     *
     * @var string
     */
    protected $directory;

    /**
     * Loads the extension
     *
     * @param Array $configs
     * @param ContainerBuilder $container
     *
     * @return void
     * @author Dan Cox
     */
    public function load(Array $configs, ContainerBuilder $container)
    {
        $this->configs = $configs;
        $this->container = $container;

        if (method_exists($this, 'setup'))
        {
            $this->setup();
        }

        $this->loader();

        if (method_exists($this, 'extension'))
        {
            $this->extension();
        }
    }

    /**
     * Creates a loader instance
     *
     * @return void
     * @author Dan Cox
     */
    public function loader()
    {
        if (!is_null($this->directory))
        {
            $this->loader = new YamlFileLoader($this->container, new FileLocator($this->directory));
        }
    }

    /**
     * Returns the Alias
     *
     * @return String
     * @author Dan Cox
     */
    public function getAlias()
    {   
        return (!is_null($this->alias) ? $this->alias : 'extension');
    }

    /**
     * returns the namespace for xml files (not used)
     *
     * @return string
     * @author Dan Cox
     */
    public function getNamespace()
    {
        return 'extension';
    }

    /**
     * Returns base path for XSD files.
     *
     * @return void
     * @author Dan Cox
     */
    public function getXsdValidationBasePath()
    {
        return;
    }

} // END class Extension implements ExtensionInterface

