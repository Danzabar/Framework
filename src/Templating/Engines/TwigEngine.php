<?php namespace Wasp\Templating\Engines;

use Symfony\Component\Templating\EngineInterface,
	\Twig_Loader_Filesystem as Loader,
	\Twig_Environment as Environment;

/**
 * Custom engine for twig templating
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class TwigEngine implements EngineInterface
{
	/**
	 * Filesystem loader
	 *
	 * @var Object
	 */
	protected $loader;

	/**
	 * Twig environment
	 *
	 * @var Object
	 */
	protected $environment;

	/**
	 * Instance of the FileSystem
	 *
	 * @var \Symfony\Component\Filesystem\Filesystem
	 **/
	protected $fs;

	/**
	 * Instance of the template class
	 *
	 * @var \Wasp\Templating\Template
	 **/
	protected $template;

	/**
	 * An instance of the twig specific container
	 *
	 * @var \Wasp\Templating\DIAwareTwigContainer
	 */
	protected $container;

	/**
	 * Load dependencies
	 *
	 * @param \Wasp\Templating\Template $template $template
	 * @param \Symfony\Component\Filesystem\Filesystem $fs
	 * @param \Wasp\Templating\DIAwareTwigContainer $twigContainer
	 * @author Dan Cox
	 **/
	public function __construct($template, $fs, $twigContainer)
	{
		$this->template = $template;
		$this->fs = $fs;
		$this->container = $twigContainer;
	}

	/**
	 * Creates the environment
	 *
	 * @param Array $settings
	 * @return void
	 * @author Dan Cox
	 */
	public function create(Array $settings = Array())
	{
		$this->loader = new Loader($this->template->getDirectory());
		$this->environment = new Environment($this->loader, $settings);

		// Add Globals
		$this->environment->addGlobal('app', $this->container);
	}

	/**
	 * Check if twig supports this file
	 *
	 * @param String $name
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function supports($name)
	{
		return (strstr($name, '.twig') !== false);
	}

	/**
	 * Check if the file exists
	 *
	 * @param String $name
	 * @return Boolean
	 * @author Dan Cox
	 */
	public function exists($name)
	{
		return $this->fs->exists($this->template->getDirectory() . $name);
	}

	/**
	 * Renders the template
	 *
	 * @param String $name
	 * @param Array $params
	 * @return String
	 * @author Dan Cox
	 */
	public function render($name, Array $params = Array())
	{
		return $this->environment->render($name, $params);	
	}

} // END class TwigEngine implements EngineInterface
