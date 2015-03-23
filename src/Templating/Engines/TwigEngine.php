<?php namespace Wasp\Templating\Engines;

use Symfony\Component\Templating\EngineInterface,
	Wasp\DI\DependencyInjectionAwareTrait,
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
	use DependencyInjectionAwareTrait;

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
	 * Creates the environment
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function create()
	{
		$this->loader = new Loader($this->DI->get('template')->getDirectory());
		$this->environment = new Environment($this->loader, []);
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
		$fs = $this->DI->get('fs');

		return $fs->exists($this->DI->get('template')->getDirectory() . $name);
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
